<?php
include("includes/sql.inc");

$region = htmlspecialchars($_GET["region"] ?? null);
$season = htmlspecialchars($_GET['season'] ?? $currentseason - 1); // default to last season if not specified.

// --- Query 1: resolve region + season ---
$tsql = "
SELECT
    Region.Name AS 'RegionName',
    Region.Id AS 'RegionId',
    Season.Year AS 'SeasonYear',
    Season.Id AS 'SeasonId'
FROM Season, Region
WHERE Region.Synonym = ? -- $region
AND Season.SeasonNumber = ? -- $season";

$result = sqlsrv_query($sqlConnection, $tsql, array($region, $season));
if ($result === false) {
    die("Query failed.");
}

$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
$regionName = $row['RegionName'];
$regionId = $row['RegionId'];
$seasonYear = $row['SeasonYear'];
$seasonId = $row['SeasonId'];

if (is_null($regionName)) {
    echo '<p>Unexpected region or season number.</p>';
    exit;
}

// --- Query 2: all scores + results for finals competitions this region/season ---
// League finals are a bit complicated due to the varying formats used over the years.
$tsql2 = "
DECLARE @seasonId AS INTEGER = ?; -- $seasonId
DECLARE @regionId AS INTEGER = ?; -- $regionId

-- select all scores from comps in finals this season
SELECT
    LeagueFinal.CompetitionId AS 'CompetitionId',
    LeagueFinal.Round AS 'Round',
    LeagueFinal.Description AS 'Description',
    Machine.Id AS 'MachineId',
    Machine.Name AS 'MachineName',
    Player.Id AS 'PlayerId',
    Player.Name AS 'PlayerName',
    Score.Score AS 'GameScore',
    RANK() OVER (PARTITION BY LeagueFinal.CompetitionId, Score.MachineId ORDER BY Score.Score DESC) AS 'Position',
    null AS 'ResultPoints',
    (
        SELECT TOP 1 PBScore.Score
        FROM Score PBScore
        WHERE PBScore.PlayerId = Score.PlayerId AND PBScore.MachineId = Score.MachineId
        ORDER BY PBScore.Score DESC
    ) AS 'PersonalBestScore',
    (
        SELECT TOP 1 HighScore.Score
        FROM Score HighScore
        WHERE HighScore.MachineId = Score.MachineId
        ORDER BY HighScore.Score DESC
    ) AS 'LeagueHighScore',
    (
        SELECT COUNT(PlayCount.Score)
        FROM Score PlayCount
        WHERE PlayCount.PlayerId = Score.PlayerId AND PlayCount.MachineId = Score.MachineId
    ) AS 'PlayCount'
FROM Score
INNER JOIN LeagueFinal ON LeagueFinal.CompetitionId = Score.CompetitionId
INNER JOIN Player ON Player.Id = Score.PlayerId
INNER JOIN Machine ON Machine.Id = Score.MachineId
WHERE LeagueFinal.SeasonId = @seasonId
AND LeagueFinal.RegionId = @regionId

UNION ALL

-- select all results
SELECT
    Result.CompetitionId AS 'CompetitionId',
    LeagueFinal.Round AS 'Round',
    LeagueFinal.Description + ' Results' AS 'Description',
    null AS 'MachineId',
    null AS 'MachineName',
    Player.Id AS 'PlayerId',
    Player.Name AS 'PlayerName',
    null AS 'GameScore',
    Result.Position AS 'Position',
    Result.Points AS 'ResultPoints',
    null AS 'PersonalBestScore',
    null AS 'LeagueHighScore',
    null AS 'PlayCount'
FROM Result
INNER JOIN Player ON Player.Id = Result.PlayerId
INNER JOIN LeagueFinal ON LeagueFinal.CompetitionId = Result.CompetitionId
WHERE Result.CompetitionId IN (SELECT CompetitionId FROM LeagueFinal WHERE LeagueFinal.SeasonId = @seasonId AND LeagueFinal.RegionId = @regionId)

ORDER BY Round ASC, Machine.Name DESC, GameScore DESC, Position
";

$finalsResult = sqlsrv_query($sqlConnection, $tsql2, array($seasonId, $regionId));
if ($finalsResult === false) {
    die("Query failed.");
}

// --- Build an ordered list of panels: each is either a set of per-machine ---
// --- score tables, or a single results table (mirrors the old grouping     ---
// --- logic, which starts a new panel whenever the round/description name   ---
// --- changes — score rounds and their "... Results" round are separate).  ---
$panels = [];
$lastCompName = "";
$lastMachineName = "";

while ($finalsRow = sqlsrv_fetch_array($finalsResult, SQLSRV_FETCH_ASSOC))
{
    $compName = $finalsRow['Description'];
    $machineId = $finalsRow['MachineId'];
    $machineName = $finalsRow['MachineName'];
    $playerId = $finalsRow['PlayerId'];
    $playerName = $finalsRow['PlayerName'];
    $competitionId = $finalsRow['CompetitionId'];
    $position = $finalsRow['Position'];
    $resultPoints = $finalsRow['ResultPoints'];
    $isScoreRow = ($machineId !== null);

    $newPanel = ($compName !== $lastCompName);
    if ($newPanel)
    {
        $panels[] = array(
            'title' => $compName,
            'type' => $isScoreRow ? 'scores' : 'results',
            'machines' => array(),
            'results' => array(),
        );
        $lastMachineName = ""; // force a new machine table under this panel
    }
    $lastCompName = $compName;
    $panelIndex = count($panels) - 1;

    if ($isScoreRow)
    {
        if ($machineName !== $lastMachineName)
        {
            $panels[$panelIndex]['machines'][] = array(
                'id' => $machineId,
                'name' => $machineName,
                'scores' => array(),
            );
        }
        $lastMachineName = $machineName;

        $gameScore = number_format($finalsRow['GameScore']);
        $pbScore = number_format($finalsRow['PersonalBestScore']);
        $hsScore = number_format($finalsRow['LeagueHighScore']);
        $playCount = $finalsRow['PlayCount'];

        $machineIndex = count($panels[$panelIndex]['machines']) - 1;
        $panels[$panelIndex]['machines'][$machineIndex]['scores'][] = array(
            'position' => $position,
            'playerId' => $playerId,
            'playerName' => $playerName,
            'competitionId' => $competitionId,
            'score' => $gameScore,
            'isHS' => ($gameScore === $hsScore),
            'isPB' => ($gameScore === $pbScore && $playCount > 1),
        );
    }
    else
    {
        $panels[$panelIndex]['results'][] = array(
            'position' => $position,
            'playerId' => $playerId,
            'playerName' => $playerName,
            'points' => $resultPoints !== null ? number_format($resultPoints) : null,
        );
    }
}

$pageTitle = 'UK Pinball League - ' . htmlspecialchars($regionName) . ' Regional Finals ' . $seasonYear;
$pageDescription = $pageTitle . '.';
?>
<?php require_once('includes/header-modern.inc'); ?>

    <!-- ===== PAGE HERO ===== -->
    <div class="page-hero">
        <p class="page-hero-eyebrow">Season <?= $season ?> &bull; <?= $seasonYear ?></p>
        <h1 class="page-hero-title"><?= htmlspecialchars($regionName) ?> Regional Finals</h1>
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

        <?php if (empty($panels)): ?>
        <div class="card">
            <div class="card-body">
                <p style="color: var(--gray-500);">No regional finals results have been recorded for this region and season yet.</p>
            </div>
        </div>
        <?php endif; ?>

        <?php foreach ($panels as $panel): ?>

            <?php if ($panel['type'] === 'scores'): ?>

                <!-- Round heading -->
                <div class="card-header" style="padding-left: 0; padding-right: 0;">
                    <div class="card-accent"></div>
                    <h2><?= htmlspecialchars($panel['title']) ?></h2>
                </div>

                <!-- Per-machine score grid -->
                <div class="meet-grid">
                    <?php foreach ($panel['machines'] as $machine): ?>
                    <div class="card">
                        <div class="card-header">
                            <div class="card-accent"></div>
                            <h2>
                                <a href="machine-info.php?machineid=<?= $machine['id'] ?>"
                                   style="color:inherit;text-decoration:none;"
                                   onmouseover="this.style.color='var(--amber-dark)'"
                                   onmouseout="this.style.color='inherit'">
                                    <?= htmlspecialchars($machine['name']) ?>
                                </a>
                            </h2>
                        </div>
                        <div class="card-body" style="padding: 0;">
                            <table class="meet-score-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Player</th>
                                        <th>Score</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($machine['scores'] as $s): ?>
                                    <tr>
                                        <td><?= $s['position'] ?></td>
                                        <td>
                                            <a href="scores.php?playerid=<?= $s['playerId'] ?>&machineid=<?= $machine['id'] ?>&competitionid=<?= $s['competitionId'] ?>">
                                                <?= htmlspecialchars($s['playerName']) ?>
                                            </a>
                                        </td>
                                        <td><?= $s['score'] ?></td>
                                        <td>
                                            <?php if ($s['isHS']): ?>
                                                <span class="badge-hs">HS</span>
                                            <?php elseif ($s['isPB']): ?>
                                                <span class="badge-pb">PB</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

            <?php else: ?>

                <!-- Results table -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-accent"></div>
                        <h2><?= htmlspecialchars($panel['title']) ?></h2>
                    </div>
                    <div class="card-body" style="padding: 0;">
                        <table class="results-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Player</th>
                                    <th>Points</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($panel['results'] as $r): ?>
                                <tr>
                                    <td><?= $r['position'] ?></td>
                                    <td>
                                        <a href="player-info.php?playerid=<?= $r['playerId'] ?>">
                                            <?= htmlspecialchars($r['playerName']) ?>
                                        </a>
                                    </td>
                                    <td><?= $r['points'] ?? '' ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            <?php endif; ?>

        <?php endforeach; ?>

        <!-- Notes -->
        <?php if (!empty($panels)): ?>
        <div class="card">
            <div class="card-body">
                <p class="meet-notes">
                    <span class="note-hs">HS</span> - Current league high score for this machine.<br>
                    <span class="note-pb">PB</span> - Personal best score for this player on this machine.
                </p>
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
            window.location.href = 'regional-finals.php?region=<?= $region ?>&season=' + this.value;
        });
    }
})();
</script>

<?php require_once('includes/footer-modern.inc'); ?>
