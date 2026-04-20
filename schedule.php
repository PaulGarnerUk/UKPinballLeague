<?php
// Load environment vars and database connection (sql.inc pulls in envvars.inc)
include("includes/sql.inc");

// --- Query: All meets and regional finals for the current season ---
$tsql = "
DECLARE @CurrentSeason INT = ?

SELECT
    Region.SortOrder,
    Region.Id AS 'RegionId',
    Region.Name AS 'RegionName',
    Region.Synonym AS 'RegionSynonym',
    0 AS 'CompetitionType',
    LeagueMeet.Id AS 'LeagueMeetId',
    LeagueMeet.MeetNumber AS 'LeagueMeetNumber',
    LeagueMeet.Status AS 'Status',
    LeagueMeet.Date AS 'Date',
    LeagueMeet.Host AS 'Host',
    COALESCE(LeagueMeet.Location, LeagueMeet.Address) AS 'Location',
    NULL AS 'LeagueRegionalFinalId'
FROM Region
    LEFT OUTER JOIN LeagueMeet ON LeagueMeet.RegionId = Region.Id
    AND LeagueMeet.SeasonId = @CurrentSeason
    AND (LeagueMeet.Status <> 4 OR LeagueMeet.Date > GETDATE())

UNION ALL

SELECT
    Region.SortOrder,
    Region.Id AS 'RegionId',
    Region.Name AS 'RegionName',
    Region.Synonym AS 'RegionSynonym',
    2 AS 'CompetitionType',
    NULL AS 'LeagueMeetId',
    NULL AS 'LeagueMeetNumber',
    LeagueRegionalFinal.Status AS 'Status',
    LeagueRegionalFinal.Date AS 'Date',
    LeagueRegionalFinal.Host AS 'Host',
    COALESCE(LeagueRegionalFinal.Location, LeagueRegionalFinal.Address) AS 'Location',
    LeagueRegionalFinal.Id AS 'LeagueRegionalFinalId'
FROM Region
    INNER JOIN LeagueRegionalFinal ON LeagueRegionalFinal.RegionId = Region.Id
    AND LeagueRegionalFinal.SeasonId = @CurrentSeason

ORDER BY Region.SortOrder, Date, LeagueRegionalFinalId ASC
";

$result = sqlsrv_query($sqlConnection, $tsql, array($currentseason));

// Collect all rows grouped by region (preserving sort order)
$regions = [];
if ($result) 
{
    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) 
    {
        $rName = $row['RegionName'];
        if (!array_key_exists($rName, $regions)) 
        {
            $regions[$rName] = 
            [
                'synonym' => $row['RegionSynonym'],
                'meets'   => [],
            ];
        }
        
        // Only store rows that represent an actual meet or final
        if ($row['LeagueMeetNumber'] > 0 || $row['LeagueRegionalFinalId'] > 0) 
        {
            $regions[$rName]['meets'][] = $row;
        }
    }
}
$pageTitle = 'UK Pinball League — Schedule';
$pageDescription = "Schedule of UK Pinball League meets for season $currentseason.";
$yearLabel = '20' . ($currentseason + 7);
?>
<?php require_once('includes/header-modern.inc'); ?>

    <!-- ===== PAGE HERO ===== -->
    <div class="page-hero">
        <p class="page-hero-eyebrow">Season <?= $currentseason ?> &bull; <?= $yearLabel ?></p>
        <h1 class="page-hero-title">League Schedule</h1>
    </div>

    <!-- ===== MAIN CONTENT ===== -->
    <main class="site-content">

        <!-- Intro card -->
        <div class="card intro-card">
            <div class="card-body">
                <p>The UK Pinball League season period is based on a calendar year. Each region usually consists of up to six meets. Meets for season <?= $currentseason ?> are currently being arranged and will be updated here as they are confirmed.</p>
                <p>At the end of the league season (and after any regional finals event) the top two players from each region are invited to compete in a national finals tournament.</p>
                <p>For a full calendar of other pinball events see Lecari's excellent <a href="https://www.pinballinfo.com/community/threads/uk-events-diary-2024.54814/" target="_blank" rel="noopener">Events</a> page on the <a href="http://www.pinballinfo.com" target="_blank" rel="noopener">Pinball Info forums</a>.</p>
            </div>
        </div>

        <!-- ===== REGION CARDS ===== -->
        <?php foreach ($regions as $regionName => $regionData):
            $meets = $regionData['meets'];
            $synonym = $regionData['synonym'];
        ?>
        <div class="card">
            <div class="card-header">
                <div class="card-accent"></div>
                <h2><?= htmlspecialchars($regionName) ?> League</h2>
            </div>

            <?php if (empty($meets)): ?>
                <div class="card-body">
                    <p class="no-schedule">Awaiting schedule.</p>
                </div>

            <?php else: ?>
                <div class="card-body" style="padding: 0;">
                    <table class="sched-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th class="th-location col-location">Location</th>
                                <th>Host</th>
                                <th class="th-action">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($meets as $meet):
                                $isFinal = ($meet['LeagueRegionalFinalId'] > 0);
                                $meetNumber = $meet['LeagueMeetNumber'];
                                $status = $meet['Status'];
                                $date = $meet['Date'] ? $meet['Date']->format('D jS M Y') : '—';
                                $location = $isFinal ? 'By Invitation' : htmlspecialchars((string)$meet['Location']);
                                $host = $isFinal ? 'Regional Finals' : htmlspecialchars((string)$meet['Host']);

                                $resultsLink = (!$isFinal && $meetNumber) ? "leaguemeet.php?season={$currentseason}&region={$synonym}&meet={$meetNumber}" : null;
                                $infoLink = (!$isFinal && $meetNumber) ? "schedule-info.php?season={$currentseason}&region={$synonym}&meet={$meetNumber}" : null;

                                $rowClass = trim(
                                    ($status == 4 ? 'row-cancelled' : '') . ' ' .
                                    ($isFinal ? 'row-final' : '')
                                );
                            ?>

                            <tr class="<?= $rowClass ?>">

                                <td class="col-date">
                                    <?= $date ?>
                                    <?php if ($status == 5): ?>
                                        <span class="badge badge-rescheduled">Rescheduled</span>
                                    <?php endif; ?>
                                    <?php if ($isFinal): ?>
                                        <span class="badge badge-final">Finals</span>
                                    <?php endif; ?>
                                </td>

                                <td class="col-location"><?= $location ?></td>

                                <td class="col-host"><?= $host ?></td>

                                <td class="col-action">
                                    <?php if ($status == 3 && $resultsLink): ?>
                                        <a href="<?= $resultsLink ?>" class="badge badge-results">Results →</a>

                                    <?php elseif ($status == 4): ?>
                                        <span class="badge badge-cancelled">Cancelled</span>

                                    <?php elseif ($infoLink): ?>
                                        <a href="<?= $infoLink ?>" class="badge badge-info">Info →</a>

                                    <?php endif; ?>
                                </td>

                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>

    </main>

<?php require_once('includes/footer-modern.inc'); ?>
