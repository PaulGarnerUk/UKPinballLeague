<?php
// Load environment vars and database connection
include("includes/sql.inc");

// --- Query 1: Popular Machines ---
$tsql_machines = "
SELECT TOP(20)
    Score.MachineId AS 'MachineId',
    Machine.Name AS 'MachineName',
    COUNT(Score.Score) AS 'GamesPlayed'
FROM Score
INNER JOIN Machine ON Machine.Id = Score.MachineId
GROUP BY MachineId, Machine.Name
ORDER BY COUNT(Score.Score) DESC, Machine.Name ASC
";
$result_machines = sqlsrv_query($sqlConnection, $tsql_machines);

// --- Query 2: Site-wide Stats ---
$tsql_stats = "
SELECT
    COUNT(DISTINCT Score.PlayerId) AS 'PlayerCount',
    COUNT(DISTINCT Score.MachineId) AS 'MachineCount',
    SUM(Score) AS 'TotalScore'
FROM Score
";
$result_stats = sqlsrv_query($sqlConnection, $tsql_stats);

$stats = $result_stats ? sqlsrv_fetch_array($result_stats, SQLSRV_FETCH_ASSOC) : [];
$playerCount = isset($stats['PlayerCount'])  ? number_format($stats['PlayerCount']) : '—';
$machineCount= isset($stats['MachineCount']) ? number_format($stats['MachineCount']) : '—';
$totalScore  = isset($stats['TotalScore'])   ? number_format($stats['TotalScore']) : '—';
$pageTitle = 'UK Pinball League - Home';
$pageDescription = 'The UK Pinball League - Home of the UK\'s official nationwide pinball league, running since 2006.';
?>

<?php require_once('includes/header-modern.inc'); ?>

    <!-- ===== HERO ===== -->
    <section class="hero">
        <img class="hero-image" src="images/homepage-image1.jpg" alt="Players competing at the UK Pinball League">
        <div class="hero-overlay">
            <p class="hero-eyebrow">Est. 2006 &bull; Eight Regions Across the UK</p>
            <h1 class="hero-title">UK Pinball League</h1>
        </div>
    </section>

    <!-- ===== MAIN CONTENT ===== -->
    <main class="site-content">

        <div class="content-grid">

            <!-- About card -->
            <div class="card">
                <div class="card-header">
                    <div class="card-accent"></div>
                    <h2>What's it all about?</h2>
                </div>
                <div class="card-body about-text">
                    <p>The UK Pinball League was set up in 2006 as the first pinball league in the UK. It is currently divided into eight separate regions — South West, Midlands, London &amp; South East, Northern, Scottish, Irish, East Anglia and South Wales.</p>

                    <p>The UK Pinball League offers you a chance to play a wide variety of pinball machines against players of varying standards in a friendly and sociable atmosphere.</p>

                    <p>Anyone is welcome to join at any time of the season. Please <a href="contacts.php">contact</a> the co-ordinator of your region for more information.</p>

                    <p>Playing earns you points in your regional league. At the end of the season, the top players from each region qualify for the national league final.</p>

                    <p>We have a list of common <a href="faq.php">FAQs</a> which should answer any questions. Competing also earns you World Pinball Player Ranking (<a href="https://www.ifpapinball.com/rankings/country.php" target="_blank" rel="noopener">WPPR</a>) points that rank you against all other players worldwide.</p>

                    <div class="cta-banner">What are you waiting for? Join the UK Pinball League now!</div>
                </div>
            </div>

            <!-- Popular Machines card -->
            <div class="card">
                <div class="card-header">
                    <div class="card-accent"></div>
                    <h2>Popular Machines</h2>
                </div>
                <div class="card-body" style="padding: 0;">
                    <table class="machines-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Machine</th>
                                <th>Plays</th>
                            </tr>
                        </thead>
                        <tbody>
<?php
$position = 0;
$hiddenPositions = 0;
$lastGamesPlayed = 0;

while ($row = sqlsrv_fetch_array($result_machines, SQLSRV_FETCH_ASSOC)):
    $gamesPlayed = $row['GamesPlayed'];
    $machineId = htmlspecialchars($row['MachineId']);
    $machineName = htmlspecialchars($row['MachineName']);

    if ($gamesPlayed != $lastGamesPlayed)
    {
        $lastGamesPlayed = $gamesPlayed;
        $position = $hiddenPositions + $position + 1;
        $hiddenPositions = 0;
    }
    else
    {
        ++$hiddenPositions;
    }

?>
                            <tr>
                                <td><?= $position ?></td>
                                <td><a href="machine-info.php?machineid=<?= $machineId ?>"><?= $machineName ?></a></td>
                                <td><?= number_format($gamesPlayed) ?></td>
                            </tr>
<?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <a href="machines.php?region=all&season=all&sort=plays&dir=desc">See full machine list</a>
                </div>
            </div>

        </div><!-- /.content-grid -->

        <!-- Stats Bar -->
        <div class="stats-bar">
            <div class="stat-item">
                <span class="stat-number"><?= $playerCount ?></span>
                <span class="stat-label">League Players</span>
            </div>
            <div class="stat-item">
                <span class="stat-number"><?= $machineCount ?></span>
                <span class="stat-label">Pinball Machines</span>
            </div>
            <div class="stat-item">
                <span class="stat-number"><?= $totalScore ?></span>
                <span class="stat-label">Points Scored</span>
            </div>
        </div>

    </main>

<?php require_once('includes/footer-modern.inc'); ?>
