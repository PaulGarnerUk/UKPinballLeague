<?php
require_once("includes/sql.inc");
require_once("functions/regioninfo.inc");
require_once("functions/seasoninfo.inc");

$sort = htmlspecialchars($_GET["sort"] ?? "machine"); // 'plays', 'machine', 'meets', 'player', 'score'
$dir = htmlspecialchars($_GET["dir"] ?? "asc");        // 'asc' or 'desc'
$regionParam = htmlspecialchars($_GET["region"] ?? "all");
$seasonParam = htmlspecialchars($_GET["season"] ?? "all");

// Validate region and season
$region = ValidateRegionSynonym($regionParam, $sqlConnection);
if (is_null($region)) {
    echo '<p>Unexpected region.</p>';
    exit;
}

$season = ValidateSeasonNumber($seasonParam, $sqlConnection);
if (is_null($season)) {
    echo '<p>Unexpected season.</p>';
    exit;
}

// Sort direction
if ($dir === "desc") {
    $sortdir = "DESC";
    $sortchar = "&#9660;"; // ▼
    $oppositesortdir = "asc";
} else {
    $sortdir = "ASC";
    $sortchar = "&#9650;"; // ▲
    $oppositesortdir = "desc";
}

// Sort column
if ($sort === "plays") {
    $sortColumn = 'GamesPlayed';
    $orderby = "ORDER BY GamesPlayed $sortdir, Appearances $sortdir";
} elseif ($sort === "meets") {
    $sortColumn = 'Appearances';
    $orderby = "ORDER BY Appearances $sortdir, GamesPlayed $sortdir";
} elseif ($sort === "player") {
    $sortColumn = 'PlayerName';
    $orderby = "ORDER BY PlayerName $sortdir, Machine.Name ASC";
} elseif ($sort === "score") {
    $sortColumn = 'HighScore';
    $orderby = "ORDER BY HighScore $sortdir, Machine.Name ASC";
} else {
    $sort = "machine";
    $sortColumn = 'MachineName';
    $orderby = "ORDER BY MachineName $sortdir";
}

// Build filter clause
$filterClause = "";
$tsqlParams = "";

if ($region->regionId > 0) {
    $filterClause = "WHERE (LeagueMeet.RegionId = @RegionId)";
    $tsqlParams = "DECLARE @RegionId INT = $region->regionId;";
}

if ($season->seasonId > 0) {
    if ($region->regionId > 0) {
        $filterClause .= "\r\nAND (LeagueMeet.SeasonId = @SeasonId OR LeagueFinal.SeasonId = @SeasonId)";
        $tsqlParams .= "\r\nDECLARE @SeasonId INT = $season->seasonId;";
    } else {
        $filterClause = "WHERE (LeagueMeet.SeasonId = @SeasonId OR LeagueFinal.SeasonId = @SeasonId)";
        $tsqlParams = "DECLARE @SeasonId INT = $season->seasonId;";
    }
}

$finalFilterClause = str_replace("WHERE", "AND", $filterClause);

$tsql = "
$tsqlParams

WITH AverageScore AS
(
    SELECT
    MachineId,
    AVG(Score.Score) AS 'Average'
    FROM Score
    LEFT OUTER JOIN LeagueMeet ON LeagueMeet.CompetitionId = Score.CompetitionId
    LEFT OUTER JOIN LeagueFinal ON LeagueFinal.CompetitionId = Score.CompetitionId
    $filterClause
    GROUP BY MachineId
),
GamesPlayed AS
(
    SELECT
    MachineId,
    COUNT(DISTINCT(Score.CompetitionId)) AS 'Appearances',
    COUNT(Score.Score) AS 'GamesPlayed'
    FROM Score
    LEFT OUTER JOIN LeagueMeet ON LeagueMeet.CompetitionId = Score.CompetitionId
    LEFT OUTER JOIN LeagueFinal ON LeagueFinal.CompetitionId = Score.CompetitionId
    $filterClause
    GROUP BY MachineId
),
MaxScore AS
(
    SELECT
    Score.CompetitionId,
    MachineId,
    Score,
    PlayerId,
    ROW_NUMBER() OVER (PARTITION BY MachineId ORDER BY Score DESC) Rank
    FROM Score
    LEFT OUTER JOIN LeagueMeet ON LeagueMeet.CompetitionId = Score.CompetitionId
    LEFT OUTER JOIN LeagueFinal ON LeagueFinal.CompetitionId = Score.CompetitionId
    $filterClause
)

SELECT
MaxScore.MachineId AS 'MachineId',
Machine.Name AS 'MachineName',
GamesPlayed.Appearances AS 'Appearances',
GamesPlayed.GamesPlayed AS 'GamesPlayed',
MaxScore.Score AS HighScore,
AverageScore.Average AS AverageScore,
MaxScore.PlayerId AS 'PlayerId',
Player.Name AS 'PlayerName',
MaxScore.CompetitionId
FROM MaxScore
INNER JOIN AverageScore ON AverageScore.MachineId = MaxScore.MachineId
INNER JOIN GamesPlayed ON GamesPlayed.MachineId = MaxScore.MachineId
INNER JOIN Machine ON Machine.Id = MaxScore.MachineId
INNER JOIN Player ON Player.Id = MaxScore.PlayerId
LEFT OUTER JOIN LeagueMeet ON LeagueMeet.CompetitionId = MaxScore.CompetitionId
LEFT OUTER JOIN LeagueFinal ON LeagueFinal.CompetitionId = MaxScore.CompetitionId
WHERE MaxScore.Rank = 1
$finalFilterClause
$orderby
";

$result = sqlsrv_query($sqlConnection, $tsql);

// Buffer rows
$rows = [];
if ($result) {
    while ($r = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        $rows[] = $r;
    }
    sqlsrv_free_stmt($result);
}

// Helper: build a sort link URL
function sortUrl($sortKey, $defaultDir, $sort, $dir, $oppositesortdir, $regionParam, $seasonParam) {
    $d = ($sort === $sortKey) ? $oppositesortdir : $defaultDir;
    return "machines.php?region=$regionParam&season=$seasonParam&sort=$sortKey&dir=$d";
}
$pageTitle = 'UK Pinball League — Machines';
$pageDescription = 'All pinball machines played in the UK Pinball League.';
?>
<?php require_once('includes/header-modern.inc'); ?>

    <!-- ===== PAGE HERO ===== -->
    <div class="page-hero">
        <p class="page-hero-eyebrow"><?= htmlspecialchars($region->leagueName) ?> &bull; <?= htmlspecialchars($season->seasonName) ?></p>
        <h1 class="page-hero-title">Machines</h1>
    </div>

    <!-- ===== MAIN CONTENT ===== -->
    <main class="site-content">

        <!-- Filters -->
        <div class="card">
            <div class="card-body">
                <div class="filter-bar">
                    <div class="filter-group">
                        <label class="filter-label" for="regionSelect">League</label>
                        <select class="filter-select-light" id="regionSelect">
                            <option value="all"<?= ($regionParam === 'all' ? ' selected' : '') ?>>All Leagues</option>
                            <option value="sw"<?= ($regionParam === 'sw'  ? ' selected' : '') ?>>South West</option>
                            <option value="m"<?= ($regionParam === 'm'   ? ' selected' : '') ?>>Midlands</option>
                            <option value="lse"<?= ($regionParam === 'lse' ? ' selected' : '') ?>>London &amp; South East</option>
                            <option value="n"<?= ($regionParam === 'n'   ? ' selected' : '') ?>>Northern</option>
                            <option value="s"<?= ($regionParam === 's'   ? ' selected' : '') ?>>Scotland</option>
                            <option value="i"<?= ($regionParam === 'i'   ? ' selected' : '') ?>>Ireland</option>
                            <option value="ea"<?= ($regionParam === 'ea'  ? ' selected' : '') ?>>East Anglian</option>
                            <option value="w"<?= ($regionParam === 'w'   ? ' selected' : '') ?>>South Wales</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label class="filter-label" for="seasonSelect">Season</label>
                        <select class="filter-select-light" id="seasonSelect">
                            <option value="all"<?= ($seasonParam === 'all' ? ' selected' : '') ?>>All Seasons</option>
                            <?php for ($s = $currentseason; $s >= 1; $s--): ?>
                            <option value="<?= $s ?>"<?= ($seasonParam == $s ? ' selected' : '') ?>>Season <?= $s ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Machines Table -->
        <div class="card">
        <!--
            <div class="card-header">
                <div class="card-accent"></div>
                <h2>All Machines</h2>
            </div>
-->
            <div class="card-body" style="padding: 0; overflow-x: auto;">
                <table class="league-table">
                    <thead>
                        <tr>
                            <th class="th-pos">#</th>
                            <th class="th-player">
                                <a href="<?= sortUrl('machine', 'asc', $sort, $dir, $oppositesortdir, $regionParam, $seasonParam) ?>"<?= ($sort === 'machine' ? ' class="th-sort-active"' : '') ?>>Machine<?= ($sort === 'machine' ? ' ' . $sortchar : '') ?></a>
                            </th>
                            <th>
                                <a href="<?= sortUrl('meets', 'desc', $sort, $dir, $oppositesortdir, $regionParam, $seasonParam) ?>"<?= ($sort === 'meets' ? ' class="th-sort-active"' : '') ?>>Appearances<?= ($sort === 'meets' ? ' ' . $sortchar : '') ?></a>
                            </th>
                            <th>
                                <a href="<?= sortUrl('plays', 'desc', $sort, $dir, $oppositesortdir, $regionParam, $seasonParam) ?>"<?= ($sort === 'plays' ? ' class="th-sort-active"' : '') ?>>Plays<?= ($sort === 'plays' ? ' ' . $sortchar : '') ?></a>
                            </th>
                            <th>Avg Score</th>
                            <th>
                                <a href="<?= sortUrl('score', 'desc', $sort, $dir, $oppositesortdir, $regionParam, $seasonParam) ?>"<?= ($sort === 'score' ? ' class="th-sort-active"' : '') ?>>High Score<?= ($sort === 'score' ? ' ' . $sortchar : '') ?></a>
                            </th>
                            <th class="th-player">
                                <a href="<?= sortUrl('player', 'asc', $sort, $dir, $oppositesortdir, $regionParam, $seasonParam) ?>"<?= ($sort === 'player' ? ' class="th-sort-active"' : '') ?>>Player<?= ($sort === 'player' ? ' ' . $sortchar : '') ?></a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $position = 0;
                        $hiddenPositions = 0;
                        $lastSortedValue = null;

                        foreach ($rows as $r):
                            $machineId = $r['MachineId'];
                            $machineName = $r['MachineName'];
                            $appearances = $r['Appearances'];
                            $gamesPlayed = $r['GamesPlayed'];
                            $avgScore = number_format($r['AverageScore']);
                            $highScore = number_format($r['HighScore']);
                            $playerName = $r['PlayerName'];
                            $playerId = $r['PlayerId'];
                            $sortedValue = $r[$sortColumn];

                            if ($sortedValue != $lastSortedValue) {
                                $lastSortedValue = $sortedValue;
                                $position = $hiddenPositions + $position + 1;
                                $hiddenPositions = 0;
                            } else {
                                $hiddenPositions++;
                            }
                        ?>
                        <tr>
                            <td class="td-pos"><?= $position ?></td>
                            <td class="td-player"><a href="machine-info.php?machineid=<?= $machineId ?>&amp;region=<?= $regionParam ?>&amp;season=<?= $seasonParam ?>"><?= htmlspecialchars($machineName) ?></a></td>
                            <td><?= $appearances ?></td>
                            <td><?= $gamesPlayed ?></td>
                            <td><?= $avgScore ?></td>
                            <td><?= $highScore ?></td>
                            <td class="td-player"><a href="player-info.php?playerid=<?= $playerId ?>"><?= htmlspecialchars($playerName) ?></a></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($rows)): ?>
                        <tr>
                            <td colspan="7" style="text-align:center; padding: 24px; color: var(--gray-500); font-style:italic;">No machines found for the selected filter.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Notes -->
        <div class="card intro-card">
            <div class="card-body">
                <p>When filtered by region, only scores achieved at league meets within that region are considered.</p>
                <p><strong>Appearances</strong> is the number of league meets or league finals the game has appeared in.<br>
                <strong>Plays</strong> is the total number of games played across those events.<br>
                For example, if Twilight Zone appeared at one meet with 12 players, Appearances would be 1 and Plays would be 12.</p>
            </div>
        </div>

    </main>

<script>
(function ()
{
    function applyFilters()
    {
        var r = document.getElementById('regionSelect').value;
        var s = document.getElementById('seasonSelect').value;
        window.location.href = 'machines.php?region=' + r + '&season=' + s + '&sort=<?= $sort ?>&dir=<?= $dir ?>';
    }

    var rSel = document.getElementById('regionSelect');
    var sSel = document.getElementById('seasonSelect');
    if (rSel) rSel.addEventListener('change', applyFilters);
    if (sSel) sSel.addEventListener('change', applyFilters);
})();
</script>

<?php require_once('includes/footer-modern.inc'); ?>
