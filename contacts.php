<?php
require_once("includes/sql.inc");
require_once("includes/obfuscateEmail.inc");

$tsql = "
SELECT
    Name AS 'RegionName',
    Director AS 'DirectorName',
    DirectorEmail AS 'DirectorEmail'
FROM Region ORDER BY SortOrder ASC
";
$result = sqlsrv_query($sqlConnection, $tsql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Contact the UK Pinball League">
    <title>UK Pinball League — Contact Us</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/modern.css">
</head>
<body>

<div class="site-wrapper">

    <!-- ===== HEADER ===== -->
    <header class="site-header">
        <div class="header-inner">
            <a href="index.php" class="logo">
                <img src="images/ukpl-banner.png" alt="UK Pinball League">
            </a>

            <button class="nav-toggle" id="navToggle" aria-label="Open navigation" aria-expanded="false">
                <span></span><span></span><span></span>
            </button>

            <nav class="main-nav" id="mainNav" aria-label="Main navigation">
                <ul class="nav-list">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="schedule.php">Schedule</a></li>

                    <li class="has-dropdown">
                        <a href="#">Results</a>
                        <ul class="dropdown">
                            <li><a href="league.php?region=sw">South West</a></li>
                            <li><a href="league.php?region=m">Midlands</a></li>
                            <li><a href="league.php?region=lse">London &amp; SE</a></li>
                            <li><a href="league.php?region=n">Northern</a></li>
                            <li><a href="league.php?region=s">Scottish</a></li>
                            <li><a href="league.php?region=i">Irish</a></li>
                            <li><a href="league.php?region=ea">East Anglia</a></li>
                            <li><a href="league.php?region=w">South Wales</a></li>
                            <li><a href="finals.php">Finals</a></li>
                            <li><a href="placings.php">Meet Winners</a></li>
                            <li><a href="winners.php">League Winners</a></li>
                        </ul>
                    </li>

                    <li><a href="rankings.php">Rankings</a></li>
                    <li><a href="machines.php">Machines</a></li>

                    <li class="has-dropdown">
                        <a href="#">Rules / FAQ</a>
                        <ul class="dropdown">
                            <li><a href="rules.php">Rules</a></li>
                            <li><a href="faq.php">FAQs</a></li>
                        </ul>
                    </li>

                    <li><a href="links.php">Links</a></li>
                    <li><a href="contacts.php">Contacts</a></li>
                </ul>
            </nav>
        </div>
        <div class="nav-accent"></div>
    </header>

    <!-- ===== PAGE HERO ===== -->
    <div class="page-hero">
        <p class="page-hero-eyebrow">UK Pinball League</p>
        <h1 class="page-hero-title">Contact Us</h1>
    </div>

    <!-- ===== MAIN CONTENT ===== -->
    <main class="site-content">

        <div class="content-grid">

            <!-- Regional Directors -->
            <div class="card">
                <div class="card-header">
                    <div class="card-accent"></div>
                    <h2>Regional Directors</h2>
                </div>
                <div class="card-body" style="padding: 0;">
                    <table class="contacts-table">
                        <thead>
                            <tr>
                                <th>Region</th>
                                <th>Director</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
<?php while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['RegionName']) ?></td>
                                <td><?= htmlspecialchars($row['DirectorName']) ?></td>
                                <td><?= obfuscateEmailLink($row['DirectorEmail']) ?></td>
                            </tr>
<?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- National & Social Contacts -->
            <div class="card">
                <div class="card-header">
                    <div class="card-accent"></div>
                    <h2>National Executive Team</h2>
                </div>
                <div class="card-body" style="padding: 0;">
                    <table class="contacts-table">
                        <tbody>
                            <tr>
                                <td><span class="contact-role">Overall Coordinator</span>Paul Garner</td>
                                <td><?= obfuscateEmailLink('paul@ukpinball.com') ?></td>
                            </tr>
                            <tr>
                                <td><span class="contact-role">Vice-Overall Coordinator</span>Kate Rothwell-Jackson</td>
                                <td><?= obfuscateEmailLink('hoochtheseal@msn.com') ?></td>
                            </tr>
                            <tr>
                                <td><span class="contact-role">Marketing Coordinator</span>Kate Rothwell-Jackson</td>
                                <td><?= obfuscateEmailLink('hoochtheseal@msn.com') ?></td>
                            </tr>
                            <tr>
                                <td><span class="contact-role">Treasurer</span>Wayne Johns</td>
                                <td><?= obfuscateEmailLink('waynejohns1973@yahoo.co.uk') ?></td>
                            </tr>
                            <tr>
                                <td><span class="contact-role">Website</span>Paul Garner</td>
                                <td><?= obfuscateEmailLink('paul@ukpinball.com') ?></td>
                            </tr>
                            <tr>
                                <td colspan="2"><span class="contact-role">Facebook</span><a href="https://www.facebook.com/UKPinballLeague" target="_blank" rel="noopener">UK Pinball League Facebook group</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div><!-- /.content-grid -->

    </main>

    <!-- ===== FOOTER ===== -->
    <footer class="site-footer">
        <p>&copy; UK Pinball League <?= date('Y') ?></p>
    </footer>

</div><!-- /.site-wrapper -->

<script>

(function ()
{
    var toggle = document.getElementById('navToggle');
    var nav = document.getElementById('mainNav');
    if (!toggle || !nav) return;

    toggle.addEventListener('click', function ()
    {
        var open = nav.classList.toggle('is-open');
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
})();

</script>

</body>
</html>
