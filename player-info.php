<?php
require_once("includes/sql.inc");

$playerid = htmlspecialchars($_GET["playerid"] ?? null);
$playername = htmlspecialchars($_GET['player'] ?? null);

$sort = htmlspecialchars($_GET["sort"] ?? "machine"); // 'plays', 'machine', 'rank', 'rank_percent'
$dir = htmlspecialchars($_GET["dir"] ?? "asc");        // 'asc' or 'desc'

if ($dir === "desc") {
    $sortdir = "DESC";
    $sortchar = "&#9660;"; // ▼
    $oppositesortdir = "asc";
}
else {
    $sortdir = "ASC";
    $sortchar = "&#9650;"; // ▲
    $oppositesortdir = "desc";
}

if ($sort === "plays") {
    $orderby = "ORDER BY PlayerBest.GamesPlayed $sortdir, Machine.Name ASC";
} elseif ($sort === "rank") {
    $orderby = "ORDER BY BestScoreRank $sortdir, Machine.Name ASC";
} elseif ($sort === "rank_percent") {
    $orderby = "ORDER BY RankPercent $sortdir, Machine.Name ASC";
} else {
    $sort = "machine";
    $orderby = "ORDER BY Machine.Name $sortdir";
}

// --- Query: resolve player by id or name ---
$tsql = "
SELECT Id, Name FROM Player WHERE Id = ? -- $playerid
UNION
SELECT Id, Name FROM Player WHERE Name = ? -- $playername
";

$result = sqlsrv_query($sqlConnection, $tsql, array($playerid, $playername));
$row = ($result) ? sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC) : null;

$playerFound = ($row !== null);

if ($playerFound)
{
    $playerid = $row['Id'];
    $playername = $row['Name'];

    // --- Query: summary stats ---
    $tsqlStats = "
    SELECT
        COUNT(Id) AS 'GamesPlayed',
        COUNT(DISTINCT(MachineId)) AS 'MachinesPlayed'
    FROM Score
    WHERE PlayerId = ? -- $playerid
    ";

    $statsResult = sqlsrv_query($sqlConnection, $tsqlStats, array($playerid));
    $statsRow = ($statsResult) ? sqlsrv_fetch_array($statsResult, SQLSRV_FETCH_ASSOC) : null;
    $totalGamesPlayed = $statsRow ? $statsRow['GamesPlayed'] : 0;
    $machinesPlayed = $statsRow ? $statsRow['MachinesPlayed'] : 0;

    // --- Query: machines played ---
    $tsqlMachines = "
    DECLARE @playerId INTEGER = ?; -- $playerid

    WITH PlayerBest AS
    (
        SELECT
            Score.MachineId,
            COUNT(*) AS GamesPlayed,
            MAX(Score.Score) AS BestScore
        FROM Score
        WHERE Score.PlayerId = @playerId
        GROUP BY Score.MachineId
    )
    SELECT
        Machine.Id AS MachineId,
        Machine.Name AS MachineName,
        PlayerBest.GamesPlayed,
        PlayerBest.BestScore,
        COUNT(CASE WHEN Score.Score > PlayerBest.BestScore THEN 1 END) + 1 AS BestScoreRank,
        COUNT(Score.Id) AS TotalScores,
        ROUND(
            CAST(
                COUNT(CASE WHEN Score.Score > PlayerBest.BestScore THEN 1 END) + 1
                AS FLOAT
            ) / COUNT(Score.Id) * 100,
            2
        ) AS RankPercent
    FROM PlayerBest
    INNER JOIN Machine ON Machine.Id = PlayerBest.MachineId
    INNER JOIN Score ON Score.MachineId = PlayerBest.MachineId
    GROUP BY
        Machine.Id,
        Machine.Name,
        PlayerBest.GamesPlayed,
        PlayerBest.BestScore
    $orderby
    ";

    $machinesResult = sqlsrv_query($sqlConnection, $tsqlMachines, array($playerid));

    // Buffer rows
    $machineRows = [];
    if ($machinesResult)
    {
        while ($r = sqlsrv_fetch_array($machinesResult, SQLSRV_FETCH_ASSOC))
        {
            $machineRows[] = $r;
        }
        sqlsrv_free_stmt($machinesResult);
    }
}

$pageTitle = $playerFound
    ? "UK Pinball League - " . htmlspecialchars($playername)
    : "UK Pinball League - Player Info";
$pageDescription = $playerFound
    ? "Player information for " . htmlspecialchars($playername) . "."
    : "Player information.";

?>

<?php require_once('includes/header-modern.inc'); ?>

    <!-- ===== PAGE HERO ===== -->
    <div class="page-hero">
        <p class="page-hero-eyebrow">Player Profile</p>
        <h1 class="page-hero-title"><?= $playerFound ? htmlspecialchars($playername) : 'Player Not Found' ?></h1>
    </div>

    <!-- ===== MAIN CONTENT ===== -->
    <main class="site-content">

        <?php if (!$playerFound): ?>

            <div class="card intro-card">
                <div class="card-body">
                    <p>No player was found matching the supplied details.</p>
                </div>
            </div>

        <?php else: ?>

            <!-- Stats bar -->
            <div class="stats-bar" style="grid-template-columns: repeat(2, 1fr);">
                <div class="stat-item">
                    <span class="stat-number"><?= number_format($totalGamesPlayed) ?></span>
                    <span class="stat-label">Games Played</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?= number_format($machinesPlayed) ?></span>
                    <span class="stat-label">Machines Played</span>
                </div>
            </div>

            <!-- Machines played card -->
            <div class="card">
                <div class="card-header">
                    <div class="card-accent"></div>
                    <h2>Machines Played</h2>
                </div>
                <div class="card-body" style="padding: 0; overflow-x: auto;">
                    <table class="league-table">
                        <thead>
                            <tr>
                                <th class="th-player">
                                    <a href="player-info.php?playerid=<?= $playerid ?>&sort=machine&dir=<?= $sort === 'machine' ? $oppositesortdir : 'asc' ?>"<?= ($sort === 'machine' ? ' class="th-sort-active"' : '') ?>>Machine<?= ($sort === 'machine' ? ' ' . $sortchar : '') ?></a>
                                </th>
                                <th>
                                    <a href="player-info.php?playerid=<?= $playerid ?>&sort=plays&dir=<?= $sort === 'plays' ? $oppositesortdir : 'desc' ?>"<?= ($sort === 'plays' ? ' class="th-sort-active"' : '') ?>>Plays<?= ($sort === 'plays' ? ' ' . $sortchar : '') ?></a>
                                </th>
                                <th>Best Score</th>
                                <th>
                                    <a href="player-info.php?playerid=<?= $playerid ?>&sort=rank&dir=<?= $sort === 'rank' ? $oppositesortdir : 'asc' ?>"<?= ($sort === 'rank' ? ' class="th-sort-active"' : '') ?>>Rank<?= ($sort === 'rank' ? ' ' . $sortchar : '') ?></a>
                                </th>
                                <th>
                                    <a href="player-info.php?playerid=<?= $playerid ?>&sort=rank_percent&dir=<?= $sort === 'rank_percent' ? $oppositesortdir : 'asc' ?>"<?= ($sort === 'rank_percent' ? ' class="th-sort-active"' : '') ?>>% Top<?= ($sort === 'rank_percent' ? ' ' . $sortchar : '') ?></a>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($machineRows as $r):
                                $machineId     = $r['MachineId'];
                                $machineName   = htmlspecialchars($r['MachineName']);
                                $gamesPlayed   = $r['GamesPlayed'];
                                $bestScore     = number_format($r['BestScore']);
                                $bestScoreRank = $r['BestScoreRank'];
                                $totalScores   = number_format($r['TotalScores']);
                                $rankPercent   = number_format($r['RankPercent'], 2);
                                $machineLink   = "machine-info.php?machineid=$machineId";
                                $scoresLink    = "scores.php?playerid=$playerid&machineid=$machineId";
                                /*$isTopRank     = ($bestScoreRank === 1);
                                $rowClass      = $isTopRank ? ' class="row-rank-first"' : '';*/
                            ?>
                            <tr>
                                <td class="td-player"><a href="<?= $machineLink ?>"><?= $machineName ?></a></td>
                                <td><a href="<?= $scoresLink ?>" class="plays-link"><?= $gamesPlayed ?></a></td>
                                <td><?= $bestScore ?></td>
                                <td><?= number_format($bestScoreRank) ?> / <?= $totalScores ?></td>
                                <td><?= $rankPercent ?>%</td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($machineRows)): ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 24px; color: var(--gray-500); font-style: italic;">No machines recorded.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer" style="font-size: 12px; color: var(--gray-500); font-style: italic; background: var(--gray-50); border-top: 1px solid var(--gray-100); padding: 10px 16px;">
                    Click a plays count to view all recorded scores on that machine. Rank shows this player's best score position out of all recorded scores.
                </div>
            </div>

        <?php endif; ?>

    </main>

<?php require_once('includes/footer-modern.inc'); ?>
