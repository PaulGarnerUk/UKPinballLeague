<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= htmlspecialchars($pageDescription ?? 'The UK Pinball League — competitive pinball across the UK') ?>">
    <title><?= htmlspecialchars($pageTitle ?? 'UK Pinball League') ?></title>

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
