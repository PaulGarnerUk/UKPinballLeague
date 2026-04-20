<?php
$season = htmlspecialchars($_GET['season'] ?? '');
$region = htmlspecialchars($_GET['region'] ?? '');
$meet = htmlspecialchars($_GET['meet'] ?? '');
$excludeGuests = htmlspecialchars($_GET['exclude_guests'] ?? 'true');

include("includes/sql.inc");

// Query 1: Meet header
$tsql1 = "
SELECT
    Season.SeasonNumber,
    Region.Name AS RegionName,
    LeagueMeet.MeetNumber,
    CONVERT(varchar, LeagueMeet.Date, 103) AS Date,
    LeagueMeet.Host,
    (
        SELECT COUNT(PlayerId) FROM Result
        WHERE CompetitionId = LeagueMeet.CompetitionId
    ) AS LeaguePlayers,
    (
        SELECT COUNT(PlayerId) FROM CompetitionPlayer
        WHERE CompetitionPlayer.CompetitionId = LeagueMeet.CompetitionId
        AND CompetitionPlayer.ExcludeFromResults = 1
    ) AS ExcludedPlayers,
    LeagueMeet.CompetitionId AS CompetitionId
FROM LeagueMeet
INNER JOIN Region ON Region.Id = LeagueMeet.RegionId
INNER JOIN Season ON Season.Id = LeagueMeet.SeasonId
WHERE Region.Synonym = ?
AND Season.SeasonNumber = ?
AND LeagueMeet.MeetNumber = ?
";

$r1 = sqlsrv_query($sqlConnection, $tsql1, array($region, $season, $meet));
$leagueMeetRow = $r1 ? sqlsrv_fetch_array($r1, SQLSRV_FETCH_ASSOC) : null;

$leagueMeetRegionName = $leagueMeetRow['RegionName'] ?? '';
$leagueMeetHostName = $leagueMeetRow['Host'] ?? '';
$leagueMeetDateRaw = $leagueMeetRow['Date'] ?? '';
$leagueMeetPlayers = (int)($leagueMeetRow['LeaguePlayers'] ?? 0);
$leagueMeetExcludedPlayers = (int)($leagueMeetRow['ExcludedPlayers'] ?? 0);
$competitionId = $leagueMeetRow['CompetitionId'] ?? 0;

// Reformat date from dd/mm/yyyy to "Monday 5th April 2025"
$leagueMeetDate = $leagueMeetDateRaw;
if ($leagueMeetDateRaw) 
{
    $dt = DateTime::createFromFormat('d/m/Y', $leagueMeetDateRaw);
    if ($dt) $leagueMeetDate = $dt->format('l jS F Y');
}

$excludeGuestsBit = ($excludeGuests === 'true') ? 1 : 0;

// Query 2: All scores grouped by machine
$tsql2 = "
DECLARE @region NVARCHAR(3) = ?;
DECLARE @season INTEGER = ?;
DECLARE @MeetNumber INTEGER = ?;
DECLARE @ExcludeGuests BIT = ?;
DECLARE @CompetitionId INTEGER = ?;

SET NOCOUNT ON;
DECLARE @ExcludedPlayerIds TABLE (PlayerId INT)
IF (@ExcludeGuests = 1) BEGIN
    INSERT INTO @ExcludedPlayerIds (PlayerId)
    SELECT PlayerId FROM CompetitionPlayer
    WHERE CompetitionPlayer.CompetitionId = @CompetitionId
    AND CompetitionPlayer.ExcludeFromResults = 1
END

SELECT
    RANK() OVER (PARTITION BY Score.MachineId ORDER BY Score DESC) AS Rank,
    Score.PlayerId AS 'PlayerId',
    Player.Name AS 'PlayerName',
    Score.MachineId AS 'MachineId',
    Machine.Name AS 'MachineName',
    CompetitionMachine.Notes AS 'MachineNotes',
    Score.CompetitionId AS 'CompetitionId',
    Score.Score AS 'GameScore',
    (
        SELECT TOP 1 PBScore.Score FROM Score PBScore
        WHERE PBScore.PlayerId = Score.PlayerId AND PBScore.MachineId = Score.MachineId
        ORDER BY PBScore.Score DESC
    ) AS 'PersonalBestScore',
    (
        SELECT TOP 1 HighScore.Score FROM Score HighScore
        WHERE HighScore.MachineId = Score.MachineId
        ORDER BY HighScore.Score DESC
    ) AS 'LeagueHighScore',
    (
        SELECT COUNT(PlayCount.Score) FROM Score PlayCount
        WHERE PlayCount.PlayerId = Score.PlayerId AND PlayCount.MachineId = Score.MachineId
    ) AS 'PlayCount',
    COALESCE(CompetitionPlayer.ExcludeFromResults, 0) AS 'ExcludedPlayer'
FROM Score
INNER JOIN Player ON Player.Id = Score.PlayerId
INNER JOIN Machine ON Machine.Id = Score.MachineId
INNER JOIN LeagueMeet ON LeagueMeet.CompetitionId = Score.CompetitionId
INNER JOIN Season ON Season.Id = LeagueMeet.SeasonId
INNER JOIN Region ON Region.Id = LeagueMeet.RegionId
LEFT JOIN CompetitionPlayer ON CompetitionPlayer.CompetitionId = Score.CompetitionId
    AND CompetitionPlayer.PlayerId = Score.PlayerId
LEFT JOIN CompetitionMachine ON CompetitionMachine.CompetitionId = Score.CompetitionId
    AND CompetitionMachine.MachineId = Score.MachineId
WHERE Region.Synonym = @region
AND Season.SeasonNumber = @season
AND LeagueMeet.MeetNumber = @MeetNumber
AND Score.PlayerId NOT IN (SELECT PlayerId FROM @ExcludedPlayerIds)
ORDER BY Machine.Name, GameScore DESC, PlayerName
";

$r2 = sqlsrv_query($sqlConnection, $tsql2, array($region, $season, $meet, $excludeGuestsBit, $competitionId));

$machines = [];
if ($r2) 
{
    while ($row = sqlsrv_fetch_array($r2, SQLSRV_FETCH_ASSOC)) 
    {
        $mid = $row['MachineId'];
        if (!isset($machines[$mid])) 
        {
            $machines[$mid] = 
            [
                'id' => $mid,
                'name' => $row['MachineName'],
                'notes' => $row['MachineNotes'],
                'scores' => [],
            ];
        }
        $gameScore = number_format($row['GameScore']);
        $pbScore = number_format($row['PersonalBestScore']);
        $hsScore = number_format($row['LeagueHighScore']);
        $machines[$mid]['scores'][] = 
        [
            'rank' => $row['Rank'],
            'playerId' => $row['PlayerId'],
            'playerName' => $row['PlayerName'],
            'score' => $gameScore,
            'competitionId' => $row['CompetitionId'],
            'excludedPlayer' => $row['ExcludedPlayer'],
            'isHS' => ($gameScore === $hsScore),
            'isPB' => ($gameScore === $pbScore && $row['PlayCount'] > 1),
        ];
    }
}

// Query 3: Overall results
if ($excludeGuests === 'true') 
{
    $tsql3 = "
    SELECT
        Result.Position AS 'Rank',
        Player.Id AS 'PlayerId',
        Player.Name AS 'PlayerName',
        0 AS 'ExcludedPlayer',
        Result.Score AS 'Score',
        Result.Points AS 'Points'
    FROM Result
    INNER JOIN LeagueMeet ON LeagueMeet.CompetitionId = Result.CompetitionId
    INNER JOIN Season ON Season.Id = LeagueMeet.SeasonId
    INNER JOIN Region ON Region.Id = LeagueMeet.RegionId
    INNER JOIN Player ON Player.Id = Result.PlayerId
    WHERE Region.Synonym = ?
    AND Season.SeasonNumber = ?
    AND LeagueMeet.MeetNumber = ?
    ORDER BY Rank, PlayerName
    ";
    $r3 = sqlsrv_query($sqlConnection, $tsql3, array($region, $season, $meet));
} 
else 
{
    $tsql3 = "
    DECLARE @CompetitionId INT = ?;
    DECLARE @ExcludeNonLeaguePlayers BIT = ?;

    DECLARE @ExcludedPlayerIds TABLE (PlayerId INT)
    IF (@ExcludeNonLeaguePlayers = 1) BEGIN
        INSERT INTO @ExcludedPlayerIds (PlayerId)
        SELECT PlayerId FROM CompetitionPlayer
        WHERE CompetitionPlayer.CompetitionId = @CompetitionId
        AND CompetitionPlayer.ExcludeFromResults = 1
    END

    DECLARE @TotalPlayers INT
    SET @TotalPlayers = (
        SELECT COUNT(DISTINCT PlayerId) FROM Score
        WHERE CompetitionId = @CompetitionId
        AND PlayerId NOT IN (SELECT PlayerId FROM @ExcludedPlayerIds)
    );

    WITH MeetScores AS (
        SELECT
            Score.PlayerId, Score.MachineId, Score.Score,
            RANK() OVER (PARTITION BY Score.MachineId ORDER BY Score.Score DESC) AS 'Position',
            RANK() OVER (PARTITION BY Score.MachineId ORDER BY Score.Score ASC) AS 'Points'
        FROM Score
        WHERE CompetitionId = @CompetitionId
        AND PlayerId NOT IN (SELECT PlayerId FROM @ExcludedPlayerIds)
    ),
    BonusPoints AS (
        SELECT TopScore.PlayerId, 1 AS BonusPoint
        FROM MeetScores AS TopScore
        INNER JOIN MeetScores AS SecondScore
            ON SecondScore.MachineId = TopScore.MachineId AND SecondScore.Position = 2
        WHERE TopScore.Position = 1
        AND TopScore.Score >= (SecondScore.Score * 2)
    ),
    TotalPoints AS (
        SELECT
            MeetScores.PlayerId,
            SUM(MeetScores.Points)
            + (SELECT COALESCE(SUM(BonusPoints.BonusPoint), 0)
               FROM BonusPoints WHERE BonusPoints.PlayerId = MeetScores.PlayerId)
            AS TotalPoints
        FROM MeetScores
        GROUP BY MeetScores.PlayerId
    ),
    RankedResults AS (
        SELECT
            PlayerId,
            TotalPoints.TotalPoints AS 'Score',
            RANK() OVER (ORDER BY TotalPoints.TotalPoints DESC) AS 'Position',
            CASE
                WHEN (((ROW_NUMBER() OVER (ORDER BY TotalPoints.TotalPoints ASC)) - @TotalPlayers) + 20) < 0
                THEN 0
                ELSE (((ROW_NUMBER() OVER (ORDER BY TotalPoints.TotalPoints ASC)) - @TotalPlayers) + 20)
            END AS 'Points'
        FROM TotalPoints
    )
    SELECT
        @CompetitionId,
        r.PlayerId AS 'PlayerId',
        Player.Name AS 'PlayerName',
        COALESCE(CompetitionPlayer.ExcludeFromResults, 0) AS 'ExcludedPlayer',
        Score, Position AS 'Rank',
        CASE
            WHEN (SELECT COUNT(*) FROM RankedResults WHERE Position = r.Position) > 1
            THEN (SELECT CAST(SUM(RankedResults.Points) AS float)
                  FROM RankedResults WHERE Position = r.Position)
                 / (SELECT COUNT(*) FROM RankedResults WHERE Position = r.Position)
            ELSE r.Points
        END AS 'Points'
    FROM RankedResults r
    INNER JOIN Player ON Player.Id = r.PlayerId
    LEFT JOIN CompetitionPlayer
        ON CompetitionPlayer.PlayerId = r.PlayerId
        AND CompetitionPlayer.CompetitionId = @CompetitionId
    ORDER BY Score DESC, Player.Name ASC
    ";
    $r3 = sqlsrv_query($sqlConnection, $tsql3, array($competitionId, $excludeGuestsBit));
}

$resultsRows = [];
if ($r3) 
{
    while ($row = sqlsrv_fetch_array($r3, SQLSRV_FETCH_ASSOC)) 
    {
        $resultsRows[] = 
        [
            'rank' => $row['Rank'],
            'playerId' => $row['PlayerId'],
            'playerName' => $row['PlayerName'],
            'excludedPlayer' => $row['ExcludedPlayer'],
            'score' => (float)$row['Score'],
            'points' => round((float)$row['Points'], 2),
        ];
    }
    sqlsrv_free_stmt($r3);
}

$pageTitle = 'UK Pinball League — ' . htmlspecialchars($leagueMeetRegionName) . ' Meet ' . $meet;
$pageDescription = $pageTitle . '.';
$showGuestNote = ($leagueMeetExcludedPlayers > 0 && $excludeGuests === 'false');
?>
<?php require_once('includes/header-modern.inc'); ?>

    <!-- PAGE HERO -->
    <div class="page-hero">
        <p class="page-hero-eyebrow">
            <?= htmlspecialchars($leagueMeetRegionName) ?> League
            &bull; Season <?= $season ?>
            &bull; Meet <?= $meet ?>
        </p>
        <h1 class="page-hero-title"><?= $leagueMeetDate ?></h1>
        <?php if ($leagueMeetHostName): ?>
            <p class="page-hero-sub">Hosted by <?= htmlspecialchars($leagueMeetHostName) ?></p>
        <?php endif; ?>
    </div>

    <!-- MAIN CONTENT -->
    <main class="site-content">

        <!-- Guest player toggle -->
        <?php if ($leagueMeetExcludedPlayers > 0): ?>
        <div class="guest-toggle-wrap">
            <div class="checkbox-wrapper-13">
                <input id="toggle-guests" type="checkbox"
                    onchange="UpdateQueryParameterFromCheckbox('exclude_guests', this)"
                    <?= ($excludeGuests !== 'true') ? 'checked' : '' ?>>
                <label for="toggle-guests">
                    Show <?= $leagueMeetExcludedPlayers ?>
                    guest <?= $leagueMeetExcludedPlayers > 1 ? 'players' : 'player' ?> in results
                </label>
            </div>
        </div>
        <?php endif; ?>

        <!-- Machine score grid -->
        <?php if (!empty($machines)): ?>
        <div class="meet-grid">
            <?php foreach ($machines as $machine): ?>
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
                        <?php if ($machine['notes']): ?>
                            <span class="machine-notes-inline">(<?= htmlspecialchars($machine['notes']) ?>)</span>
                        <?php endif; ?>
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
                                <td><?= $s['rank'] ?></td>
                                <td>
                                    <a href="scores.php?playerid=<?= $s['playerId'] ?>&machineid=<?= $machine['id'] ?>&competitionid=<?= $s['competitionId'] ?>">
                                        <?= htmlspecialchars($s['playerName']) ?>
                                    </a>
                                    <?php if ($s['excludedPlayer']): ?>
                                        <span class="guest-marker">(*)</span>
                                    <?php endif; ?>
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
        <?php endif; ?>

        <!-- Overall results -->
        <?php if (!empty($resultsRows)): ?>
        <div class="card">
            <div class="card-header">
                <div class="card-accent"></div>
                <h2>Results</h2>
            </div>
            <div class="card-body" style="padding: 0;">
                <table class="results-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Player</th>
                            <th>Score</th>
                            <th>Points</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($resultsRows as $r): ?>
                        <tr>
                            <td><?= $r['rank'] ?></td>
                            <td>
                                <a href="player-info.php?playerid=<?= $r['playerId'] ?>">
                                    <?= htmlspecialchars($r['playerName']) ?>
                                </a>
                                <?php if ($r['excludedPlayer']): ?>
                                    <span class="guest-marker">(*)</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $r['score'] ?></td>
                            <td><?= $r['points'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <!-- Notes -->
        <div class="card">
            <div class="card-body">
                <p class="meet-notes">
                    <?php if ($showGuestNote): ?>
                        (*) Guest appearance from a player in a different league. Excluded from official results.<br>
                    <?php endif; ?>
                    <span class="note-hs">HS</span> — Current league high score for this machine.<br>
                    <span class="note-pb">PB</span> — Personal best score for this player on this machine (shown only if they have played it before).
                </p>
            </div>
        </div>

    </main>

<script>
function UpdateQueryParameterFromCheckbox(parameterName, checkbox)
{
    var value = checkbox.checked ? 'false' : 'true';
    var url = new URL(window.location.href);
    url.searchParams.set(parameterName, value);
    window.location.href = url.toString();
}
</script>

<?php require_once('includes/footer-modern.inc'); ?>
