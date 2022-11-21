<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?php include("favicons.php"); ?>

<link rel="stylesheet" type="text/css" href="css/flexdropdown.css" />
<link rel="stylesheet" type="text/css" href="css/ukpbl.css?v=4" />

<!-- SlickNav -->
<link rel="stylesheet" href="css/slicknav.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.6.2/modernizr.min.js"></script>

<!-- Flex dropdown -->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript" src="js/flexdropdown.js">
/***********************************************
* Flex Level Drop Down Menu- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for this script and 100s more
***********************************************/
</script>

</head>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-LXSR53EX1K"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-LXSR53EX1K');
</script>

<body>

<!-- Background -->	
<div id="background">

<!-- Container -->
<div id="container">
	
<!-- Banner -->
<div id="banner"><img src="images/ukpl-banner.png" class="banner" width="107px">

<!-- Navigation -->
<div id="nav-wrapper">
<?php include("includes/responsive-menu.inc"); ?>
</div>
</div>

<div id="main-menu">

<p class="menu-text">
<a href="index.php" class="menu">HOME</a> 
<a href="schedule.php" class="menu">SCHEDULE</a> 
<a href="#" class="menu" data-flexmenu="league-results">RESULTS</a>
<a href="rankings.php" class="menu">RANKINGS</a>
<a href="machines.php" class="menu">MACHINES</a>
<a href="#" class="menu" data-flexmenu="rules-faqs">RULES/FAQ</a> 
<a href="links.php" class="menu">LINKS</a> 
<a href="contacts.php" class="menu">CONTACTS</a>
</p>

<!--HTML for League Results-->
<ul id="league-results" class="flexdropdownmenu">
<li><a href="league.php?region=sw">SOUTH WEST</a></li>
<li><a href="league.php?region=m">MIDLANDS</a></li>
<li><a href="league.php?region=lse">LONDON &amp; SE</a></li>
<li><a href="league.php?region=n">NORTHERN</a></li>
<li><a href="league.php?region=s">SCOTTISH</a></li>
<li><a href="league.php?region=i">IRISH</a></li>
<li><a href="finals.php">FINALS</a></li>
<li><a href="placings.php">MEET WINNERS</a></li>
<li><a href="winners.php">LEAGUE WINNERS</a></li>
</ul>

<!--HTML for Rules/FAQs-->
<ul id="rules-faqs" class="flexdropdownmenu">
<li><a href="rules.php">RULES</a></li>
<li><a href="faq.php">FAQs</a></li>
</ul>

</div><!--END MENU DIV-->

<div id="menu-strip"></div>