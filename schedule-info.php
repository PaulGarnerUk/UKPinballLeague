<?php
$season = htmlspecialchars($_GET['season'] ?? '');
$region = htmlspecialchars($_GET['region'] ?? '');
$meet = htmlspecialchars($_GET['meet']   ?? '');

// sql.inc pulls in envvars.inc and creates $sqlConnection
include("includes/sql.inc");
include("includes/obfuscateEmail.inc");

// --- Query: Meet details ---
$tsql = "
SELECT
    Season.SeasonNumber,
    Region.Name AS 'RegionName',
    Region.Director AS 'RegionDirector',
    Region.DirectorEmail AS 'RegionDirectorEmail',
    LeagueMeet.CompetitionId AS 'CompetitionId',
    LeagueMeet.MeetNumber AS 'MeetNumber',
    LeagueMeet.Date AS 'MeetDate',
    LeagueMeet.Status AS 'Status',
    LeagueMeet.PracticeStart AS 'PracticeStart',
    LeagueMeet.PracticeEnd AS 'PracticeEnd',
    LeagueMeet.CompetitionStart AS 'CompetitionStart',
    LeagueMeet.CompetitionEnd AS 'CompetitionEnd',
    LeagueMeet.Host AS 'Host',
    LeagueMeet.Location AS 'Location',
    LeagueMeet.Address AS 'Address',
    LeagueMeet.PublicVenue AS 'Public'
    FROM LeagueMeet
    INNER JOIN Region ON Region.Id = LeagueMeet.RegionId
    INNER JOIN Season ON Season.Id = LeagueMeet.SeasonId
    WHERE Region.Synonym = ?
    AND Season.SeasonNumber = ?
    AND LeagueMeet.MeetNumber = ?
";

$result = sqlsrv_query($sqlConnection, $tsql, array($region, $season, $meet));
$row = ($result) ? sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC) : null;

$meetConfirmed = ($row !== null);

if ($meetConfirmed) 
{
    $regionName = htmlspecialchars($row['RegionName']);
    $meetNumber = (int)$row['MeetNumber'];
    $meetDate = $row['MeetDate']->format('l jS F Y');
    $meetStatus = (int)$row['Status'];
    $meetHost = htmlspecialchars((string)$row['Host']);
    $meetLocation = htmlspecialchars((string)$row['Location']);
    $meetAddress = htmlspecialchars((string)$row['Address']);
    $publicVenue = $row['Public'];
    $competitionId = $row['CompetitionId'];
    $regionDirector = htmlspecialchars($row['RegionDirector']);
    $regionDirectorEmailLink = obfuscateEmailLink($row['RegionDirectorEmail']);

    $showTimes = ($meetStatus == 1 || $meetStatus == 5);
    if ($showTimes) 
    {
        $practiceStart = $row['PracticeStart']->format('g:i a');
        $practiceEnd = $row['PracticeEnd']->format('g:i a');
        $competitionStart = $row['CompetitionStart']->format('g:i a');
        $competitionEnd = $row['CompetitionEnd']->format('g:i a');
    }

    switch ($meetStatus) 
    {
        case 1: $statusLabel = 'Scheduled'; $statusBadgeClass = 'badge-scheduled'; break;
        case 3: $statusLabel = 'Completed'; $statusBadgeClass = 'badge-completed'; break;
        case 5: $statusLabel = 'Rescheduled'; $statusBadgeClass = 'badge-rescheduled'; break;
        default: $statusLabel = ''; $statusBadgeClass = ''; break;
    }

    // --- Query: Machines for this competition ---
    $tsql2 = "
    SELECT
        Machine.Id AS 'MachineId',
        Machine.Name AS 'MachineName',
        CompetitionMachine.Notes AS 'Notes',
        Machine.OpdbId AS 'OpdbId',
        Machine.GuideUrl AS 'GuideUrl'
    FROM CompetitionMachine
    INNER JOIN Machine ON Machine.Id = CompetitionMachine.MachineId
    WHERE CompetitionMachine.CompetitionId = ?
    ";
    $machinesResult = sqlsrv_query($sqlConnection, $tsql2, array($competitionId));
    $hasMachines = ($machinesResult && sqlsrv_has_rows($machinesResult));
}

// Page title for <title> tag
$pageTitle = $meetConfirmed
    ? htmlspecialchars($regionName) . " League — Meet {$meetNumber}"
    : "UK Pinball League — Meet Info";
$pageDescription = 'Meet information for UK Pinball League.';
?>

<?php require_once('includes/header-modern.inc'); ?>

    <!-- ===== PAGE HERO ===== -->
    <div class="page-hero">
        <a href="schedule.php" class="page-hero-back">← Schedule</a>

        <?php if ($meetConfirmed): ?>
            <p class="page-hero-eyebrow"><?= $regionName ?> League &bull; Meet <?= $meetNumber ?></p>
            <h1 class="page-hero-title"><?= $meetDate ?></h1>
            <?php if ($statusLabel): ?>
                <div class="page-hero-badge">
                    <span class="badge <?= $statusBadgeClass ?>"><?= $statusLabel ?></span>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <p class="page-hero-eyebrow">Meet Info</p>
            <h1 class="page-hero-title">Not Yet Confirmed</h1>
        <?php endif; ?>
    </div>

    <!-- ===== MAIN CONTENT ===== -->
    <main class="site-content">

        <?php if (!$meetConfirmed): ?>
            <div class="card intro-card">
                <div class="card-body">
                    <p>This meet has not yet been confirmed. Please check back later or <a href="contacts.php">contact your regional co-ordinator</a> for more information.</p>
                </div>
            </div>

        <?php else: ?>

            <!-- Meet details card -->
            <div class="card">
                <div class="card-header">
                    <div class="card-accent"></div>
                    <h2>Meet Details</h2>
                </div>
                <div class="card-body">
                    <dl class="info-grid">

                        <dt class="info-label">Date</dt>
                        <dd class="info-value"><?= $meetDate ?></dd>

                        <?php if ($showTimes): ?>
                        <dt class="info-label">Practice</dt>
                        <dd class="info-value"><?= $practiceStart ?> – <?= $practiceEnd ?></dd>

                        <dt class="info-label">Competition</dt>
                        <dd class="info-value"><?= $competitionStart ?> – <?= $competitionEnd ?></dd>
                        <?php endif; ?>

                        <dt class="info-label">Host</dt>
                        <dd class="info-value"><?= $meetHost ?></dd>

                        <dt class="info-label">Location</dt>
                        <dd class="info-value">
                            <?php if ($publicVenue === 1): ?>
                                <?= $meetAddress ?>
                                <a href="https://www.google.com/maps?saddr=My+Location&daddr=<?= urlencode($meetAddress) ?>"
                                   class="directions-link" target="_blank" rel="noopener">Directions →</a>
                                <span class="venue-note">Public venue</span>
                            <?php else: ?>
                                <?= $meetLocation ?>
                                <?php if ($showTimes): ?>
                                    <span class="venue-note">Private venue — email <?= $regionDirector ?> for the full address: <?= $regionDirectorEmailLink ?></span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </dd>

                    </dl>
                </div>
            </div>

            <!-- Machines card -->
            <div class="card">
                <div class="card-header">
                    <div class="card-accent"></div>
                    <h2>League Games</h2>
                </div>

                <?php if (!$hasMachines): ?>
                    <div class="card-body">
                        <p class="no-schedule">To be determined.</p>
                    </div>

                <?php else: ?>
                    <?php if ($showTimes): ?>
                        <p class="subject-to-change">Machine list subject to change.</p>
                    <?php endif; ?>
                    <div class="card-body" style="padding: 0;">
                        <table class="resources-table">
                            <thead>
                                <tr>
                                    <th>Machine</th>
                                    <th class="th-resources">Resources</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($m = sqlsrv_fetch_array($machinesResult, SQLSRV_FETCH_ASSOC)):
                                    $mId    = $m['MachineId'];
                                    $mName  = htmlspecialchars($m['MachineName']);
                                    $mNotes = htmlspecialchars((string)$m['Notes']);
                                    $opdbId = htmlspecialchars((string)$m['OpdbId']);
                                    $guide  = $m['GuideUrl'];
                                    $tipsUrl   = "https://pintips.net/opdb/{$opdbId}";
                                    $videosUrl = "https://pinballvideos.com/m/?q={$opdbId}";
                                ?>
                                <tr>
                                    <td>
                                        <a href="machine-info.php?machineid=<?= $mId ?>" class="machine-name-link"><?= $mName ?></a>
                                        <?php if ($mNotes): ?>
                                            <span class="machine-notes">(<?= $mNotes ?>)</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="td-resources">
                                        <a href="<?= $tipsUrl ?>"   class="resource-link" target="_blank" rel="noopener">Tips</a>
                                        <a href="<?= $videosUrl ?>" class="resource-link" target="_blank" rel="noopener">Videos</a>
                                        <?php if ($guide): ?>
                                            <a href="<?= htmlspecialchars($guide) ?>" class="resource-link" target="_blank" rel="noopener">Guide</a>
                                        <?php else: ?>
                                            <span class="resource-link-placeholder"></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

        <?php endif; ?>

    </main>

<?php require_once('includes/footer-modern.inc'); ?>
