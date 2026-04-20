<?php
include("includes/sql.inc");
include("functions/leagueinfo.inc");

$region = htmlspecialchars($_GET["region"] ?? '');
$season = htmlspecialchars($_GET['season'] ?? $currentseason);

// --- Query 1: region name, season year, total completed meets, finals competition ---
$tsql = "
SELECT
    Region.Name AS 'RegionName',
    Season.Year AS 'SeasonYear',
    (SELECT COUNT(*) FROM LeagueMeet WHERE LeagueMeet.SeasonId = Season.Id AND LeagueMeet.RegionId = Region.Id AND LeagueMeet.Status = 3) AS 'TotalMeets',
    (SELECT MAX(competitionId) FROM LeagueFinal WHERE LeagueFinal.SeasonId = Season.Id and LeagueFinal.RegionId = Region.Id) AS 'FinalsCompetitionId'
FROM Season, Region
WHERE Region.Synonym = ?
AND Season.SeasonNumber = ?";

$result = sqlsrv_query($sqlConnection, $tsql, array($region, $season));
if ($result === false) {
    die("Query failed.");
}

$meta = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
$regionName = $meta['RegionName'];
$seasonYear = $meta['SeasonYear'];
$totalMeets = $meta['TotalMeets'];
$finalsCompetitionId = $meta['FinalsCompetitionId'];

if (is_null($regionName)) {
    echo '<p>Unexpected region or season number.</p>';
    exit;
}

$info = GetLeagueInfo($region, $season);

// --- Query 2: league standings ---
$tsql = "
DECLARE @region NVARCHAR(3) = ?; -- \$region
DECLARE @season INTEGER = ?; -- \$season
DECLARE @qualifyingMeets INTEGER = ?; -- \$info->numberOfQualifyingMeets
DECLARE @finalsCompetitionId INTEGER = ?; -- \$finalsCompetitionId

-- Simplify the region and season into id values
WITH Query (RegionId, SeasonId) AS
(
  SELECT
  Region.Id AS RegionId,
  Season.Id AS SeasonId
  FROM Region, Season
  WHERE Region.Synonym = @region
  AND Season.SeasonNumber = @season
),
-- Select completed league meets for this region/season
LeagueMeets (Id, MeetNumber, CompetitionId) AS
(
  SELECT
  Id,
  MeetNumber,
  CompetitionId
  FROM LeagueMeet
  INNER JOIN Query on LeagueMeet.RegionId = Query.RegionId AND LeagueMeet.SeasonId = Query.SeasonId
  WHERE LeagueMeet.Status = 3
),
-- Select results for this region/season
Results (MeetNumber, CompetitionId, PlayerId, Score, Points, Position, Rnk) AS
(
  SELECT
  LeagueMeets.MeetNumber,
  LeagueMeets.CompetitionId,
  Result.PlayerId,
  Result.Score,
  Result.Points,
  Result.Position,
  ROW_NUMBER() OVER (PARTITION BY PlayerId ORDER BY Points DESC) AS Rnk
  FROM Result
  INNER JOIN LeagueMeets ON Result.CompetitionId = LeagueMeets.CompetitionId
),
-- Select players for this region/season
SeasonPlayers (PlayerId, PlayerName) AS
(
	SELECT DISTINCT
	Player.Id,
	Player.Name
	FROM Result
	INNER JOIN LeagueMeets ON Result.CompetitionId = LeagueMeets.CompetitionId
	INNER JOIN Player ON Player.Id = Result.PlayerId
),
LeagueResults AS
(
	SELECT
	SeasonPlayers.PlayerId AS playerid,
	SeasonPlayers.PlayerName AS player,
	(
		SELECT COUNT(*) FROM Results WHERE Results.PlayerId = SeasonPlayers.PlayerId
	) AS played,
	MeetOne.Points as meet1,
	MeetTwo.Points as meet2,
	MeetThree.Points as meet3,
	MeetFour.Points as meet4,
	MeetFive.Points as meet5,
	MeetSix.Points as meet6,
	COALESCE(MeetOne.Points,0) + COALESCE(MeetTwo.Points,0) + COALESCE(MeetThree.Points,0) + COALESCE(MeetFour.Points,0) + COALESCE(MeetFive.Points,0) + COALESCE(MeetSix.Points,0) AS total,
	(
		SELECT
		SUM(Results.Points)
		FROM Results
		WHERE Results.PlayerId = SeasonPlayers.PlayerId AND Results.Rnk <= @qualifyingMeets
	) AS best4,
	(
		SELECT
		SUM(Results.Points)
		FROM Results
		WHERE Results.PlayerId = SeasonPlayers.PlayerId AND Results.Rnk <= (@qualifyingMeets + 1)
	) AS best5,
	(
		SELECT
		SUM(Results.Points)
		FROM Results
		WHERE Results.PlayerId = SeasonPlayers.PlayerId AND Results.Rnk <= (@qualifyingMeets + 2)
	) AS best6
	FROM SeasonPlayers
	LEFT OUTER JOIN Results AS MeetOne ON MeetOne.MeetNumber = 1 AND MeetOne.PlayerId = SeasonPlayers.PlayerId
	LEFT OUTER JOIN Results AS MeetTwo ON MeetTwo.MeetNumber = 2 AND MeetTwo.PlayerId = SeasonPlayers.PlayerId
	LEFT OUTER JOIN Results AS MeetThree ON MeetThree.MeetNumber = 3 AND MeetThree.PlayerId = SeasonPlayers.PlayerId
	LEFT OUTER JOIN Results AS MeetFour ON MeetFour.PlayerId = SeasonPlayers.PlayerId AND MeetFour.MeetNumber = 4
	LEFT OUTER JOIN Results AS MeetFive ON MeetFive.PlayerId = SeasonPlayers.PlayerId AND MeetFive.MeetNumber = 5
	LEFT OUTER JOIN Results AS MeetSix ON MeetSix.PlayerId = SeasonPlayers.PlayerId AND MeetSix.MeetNumber = 6
)

SELECT
LeagueResults.*,
RANK() OVER (ORDER BY best4 DESC, best5 DESC, best6 DESC) AS 'league_pos',
Finals.Position AS 'finals_pos',
RANK() OVER (ORDER BY -Finals.Position DESC, best4 DESC, best5 DESC, best6 DESC) AS 'pos'
FROM LeagueResults
LEFT OUTER JOIN Result AS Finals ON Finals.PlayerId = LeagueResults.PlayerId AND Finals.CompetitionId = @finalsCompetitionId
ORDER BY -Finals.Position DESC, best4 DESC, best5 DESC, best6 DESC, played ASC
";

$result2 = sqlsrv_query($sqlConnection, $tsql, array($region, $season, $info->numberOfQualifyingMeets, $finalsCompetitionId));

$rows = [];
if ($result2) {
    while ($r = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC)) {
        $rows[] = $r;
    }
}
$pageTitle = 'UK Pinball League — ' . htmlspecialchars($regionName) . ' League ' . $seasonYear;
$pageDescription = $pageTitle . '.';
?>
<?php require_once('includes/header-modern.inc'); ?>

    <!-- ===== PAGE HERO ===== -->
    <div class="page-hero">
        <p class="page-hero-eyebrow">Season <?= $season ?> &bull; <?= $seasonYear ?></p>
        <h1 class="page-hero-title"><?= htmlspecialchars($regionName) ?> League</h1>
        <div class="season-select-wrap">
            <span class="season-select-label">Season</span>
            <select class="season-select" id="seasonSelect">
                <?php for ($s = $currentseason; $s >= 1; $s--): ?>
                <option value="<?= $s ?>"<?= ($s == $season ? ' selected' : '') ?>>Season <?= $s ?></option>
                <?php endfor; ?>
            </select>
        </div>
    </div>

    <!-- ===== MAIN CONTENT ===== -->
    <main class="site-content">

        <?php if ($info->note): ?>
        <div class="card intro-card">
            <div class="card-body">
                <p><?= htmlspecialchars($info->note) ?></p>
            </div>
        </div>
        <?php endif; ?>

        <!-- League Table -->
        <div class="card">
            <div class="card-header">
                <div class="card-accent"></div>
                <h2><?= htmlspecialchars($regionName) ?> League <?= $seasonYear ?></h2>
            </div>

            <?php if (empty($rows) && $season == $currentseason): ?>
            <div class="card-body">
                <p style="color: var(--gray-500);">No results have been submitted for the current season yet. You can view previous seasons using the selector above.</p>
            </div>

            <?php elseif (empty($rows)): ?>
            <div class="card-body">
                <p style="color: var(--gray-500);">No results found for this region and season.</p>
            </div>

            <?php else: ?>
            <div class="card-body" style="padding: 0; overflow-x: auto;">
                <table class="league-table">
                    <thead>
                        <tr>
                            <th class="th-pos">Pos</th>
                            <th class="th-player">Player</th>
                            <th>Played</th>
                            <?php for ($m = 1; $m <= $totalMeets; $m++): ?>
                            <th><a href="leaguemeet.php?season=<?= $season ?>&amp;region=<?= $region ?>&amp;meet=<?= $m ?>">Meet <?= $m ?></a></th>
                            <?php endfor; ?>
                            <th>Total</th>
                            <th class="th-best">Best <?= $info->numberOfQualifyingMeets ?></th>
                            <?php if ($finalsCompetitionId !== null): ?>
                            <th><a href="regional-finals.php?season=<?= $season ?>&amp;region=<?= $region ?>">Finals</a></th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $counter = 0;
                        foreach ($rows as $r):
                            $counter++;
                            $pos = number_format($r['pos']);
                            $player = $r['player'];
                            $playerid = $r['playerid'];
                            $played = $r['played'];
                            $total = round($r['total'], 1);
                            $best = round($r['best4'], 1);
                            $best5 = round($r['best5'], 1);
                            $best6 = round($r['best6'], 1);
                            $leaguePos = number_format($r['league_pos']);
                            $finalsPos = $r['finals_pos'];

                            // Per-meet scores
                            $meets = [];
                            for ($m = 1; $m <= 6; $m++) {
                                $meets[$m] = is_null($r["meet{$m}"]) ? null : (float)$r["meet{$m}"];
                            }

                            // Row class
                            if ($counter <= $info->aQualifyingPlaces) {
                                $rowClass = 'row-qual-a';
                            } elseif ($counter <= $info->aQualifyingPlaces + $info->bQualifyingPlaces) {
                                $rowClass = 'row-qual-b';
                            } else {
                                $rowClass = '';
                            }

                            // Finals adjustment
                            $finalsDisplay = '';
                            $finalsClass = '';
                            if ($finalsCompetitionId !== null) {
                                $adj = (int)$leaguePos - (int)$pos;
                                if ($adj === 0 && $finalsPos === null) {
                                    $finalsDisplay = '-';
                                    $finalsClass = 'finals-adj-nil';
                                } elseif ($adj > 0) {
                                    $finalsDisplay = '+' . $adj;
                                    $finalsClass = 'finals-adj-up';
                                } elseif ($adj < 0) {
                                    $finalsDisplay = (string)$adj;
                                    $finalsClass = 'finals-adj-down';
                                } else {
                                    $finalsDisplay = '+0';
                                    $finalsClass = 'finals-adj-nil';
                                }
                            }
                        ?>
                        <tr class="<?= $rowClass ?>">
                            <td class="td-pos"><?= $pos ?></td>
                            <td class="td-player"><a href="player-info.php?playerid=<?= $playerid ?>"><?= htmlspecialchars($player) ?></a></td>
                            <td><?= $played ?></td>
                            <?php for ($m = 1; $m <= $totalMeets; $m++): ?>
                            <?php if ($meets[$m] !== null): ?>
                            <td><?= $meets[$m] ?></td>
                            <?php else: ?>
                            <td class="td-miss">-</td>
                            <?php endif; ?>
                            <?php endfor; ?>
                            <td><?= $total ?></td>
                            <td class="td-best"><?= $best ?></td>
                            <?php if ($finalsCompetitionId !== null): ?>
                            <td class="<?= $finalsClass ?>"><?= $finalsDisplay ?></td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>

        <!-- Qualifying legend -->
        <?php if ($info->aQualifyingPlaces > 0 || $info->bQualifyingPlaces > 0): ?>
        <div class="card">
            <div class="card-body">
                <div class="qual-legend">
                    <?php if ($info->aQualifyingPlaces > 0): ?>
                    <div class="qual-legend-item">
                        <span class="qual-swatch qual-swatch-a"></span>
                        <?= htmlspecialchars($info->aQualifyingDescription) ?>
                    </div>
                    <?php endif; ?>
                    <?php if ($info->bQualifyingPlaces > 0): ?>
                    <div class="qual-legend-item">
                        <span class="qual-swatch qual-swatch-b"></span>
                        <?= htmlspecialchars($info->bQualifyingDescription) ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

    </main>

<script>
(function ()
{
    var sel = document.getElementById('seasonSelect');
    if (sel)
    {
        sel.addEventListener('change', function ()
        {
            window.location.href = 'league.php?region=<?= $region ?>&season=' + this.value;
        });
    }
})();
</script>

<?php require_once('includes/footer-modern.inc'); ?>
