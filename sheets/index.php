<?php
// Map each sheet region to its Google Drive folder
$folders = [
    'midlands' => '1exE0MyZTtFn2FXEX2SJv1l52DKNFhYLa',
    'northern' => '1VJj9WKk3uWRKnqR9gk1FxA25RAVcLD0Z',
    'scotland' => '1gQrp64OWY-FjCj55F_zIPxzIeZoYT585',
    'london' => '1gsFQZS-I2cc0s6_yolmKX7mAv0Pxf5kU',
    'eastanglia' => '1XJvDMFm2Kautq8mSLkZXktfVdH66EP6T',
];

// Get the last part of the path: /sheets/midlands → midlands
$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$parts = explode('/', $path);

if (count($parts) === 2 && $parts[0] === 'sheets' && isset($folders[$parts[1]])) {
    // Redirect to the corresponding Google Drive folder
    header("Location: https://drive.google.com/drive/folders/" . $folders[$parts[1]], true, 301);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>UK Pinball League - Sheets</title>
<style>
body { font-family: Arial, sans-serif; padding: 2em; }
h1 { margin-bottom: 1em; }
ul { list-style: none; padding-left: 0; }
li { margin-bottom: 0.5em; }
a { text-decoration: none; color: #0078D4; }
a:hover { text-decoration: underline; }
</style>
</head>
<body>
<h1>UK Pinball League Sheets</h1>
<p>Please select your region:</p>
<ul>
<?php foreach ($folders as $region => $folderId): ?>
    <li><a href="/sheets/<?php echo htmlspecialchars($region); ?>"><?php echo ucfirst($region); ?></a></li>
<?php endforeach; ?>
</ul>
</body>
</html>