<?php
	include("../includes/envvars.inc");
	include("../includes/sql.inc");

	$output = htmlspecialchars($_GET["output"] ?? 'json'); // expect json or csv
	$machineName = isset($_GET["name"]) && $_GET["name"] !== '' ? '%' . htmlspecialchars($_GET["name"]) . '%' : null; // complicated to ensure $machineName is NULL rather than an empty string when not passed.
	$playerName = isset($_GET["player_name"]) && $_GET["player_name"] !== '' ? '%' . htmlspecialchars($_GET["player_name"]) . '%' : null; // complicated to ensure $playerName is NULL rather than an empty string when not passed.

	$tsql="
DECLARE @MachineName NVARCHAR(50) = ?; -- $machineName
DECLARE @PlayerName NVARCHAR(50) = ?; -- $playerName

WITH AverageScore AS
(
	SELECT 
	MachineId,
	AVG(Score.Score) AS 'Average'
	FROM Score
	GROUP BY MachineId
),
HighScore AS
(
	SELECT
	Score.MachineId AS 'MachineId',
	MAX(Score) AS 'Score'
	FROM Score
	GROUP BY MachineId
)
SELECT 
Machine.Id AS 'MachineId', 
Machine.Name AS 'MachineName',
AverageScore.Average AS 'AverageScore',
HighScore.Score AS 'HighScore', 
Score.PlayerId AS 'HighScorePlayerId',
Player.Name AS 'HighScorePlayerName',
Competition.Id AS 'HighScoreCompetitionId',
Competition.Name AS 'HighScoreCompetitionName'
FROM Machine
LEFT OUTER JOIN AverageScore ON AverageScore.MachineId = Machine.Id
LEFT OUTER JOIN HighScore ON HighScore.MachineId = AverageScore.MachineId
LEFT OUTER JOIN Score ON Score.MachineId = Machine.Id AND Score.Score = HighScore.Score
LEFT OUTER JOIN Player ON Player.Id = Score.PlayerId
LEFT OUTER JOIN Competition on Competition.Id = Score.CompetitionId
WHERE (@MachineName IS NULL OR Machine.Name LIKE @MachineName)
AND (@PlayerName IS NULL OR Player.Name LIKE @PlayerName)
ORDER BY MachineName
";

	$params = array($machineName, $playerName);

	$result = sqlsrv_query($sqlConnection, $tsql, $params);
	if ($result == FALSE)
	{
		echo "query borken.";
		die(print_r(sqlsrv_errors(), true));
	}

	$machines = array();
	while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) 
	{
		$machines[] = array(
			'id' => $row['MachineId'],
			'name' => $row['MachineName'],
			'average_score' => $row['AverageScore'],
			'high_score' => $row['HighScore'],
			'high_score_player_id' => $row['HighScorePlayerId'],
			'high_score_player_name' => $row['HighScorePlayerName'],
			'high_score_competition_id' => $row['HighScoreCompetitionId'],
			'high_score_competition_name' => $row['HighScoreCompetitionName'],
		);
	}

	if ($output == 'csv')
	{
		header('Content-Type: application/csv');
		header('Content-Disposition: attachment; filename="machines.csv"');
		
		// Open output stream, writing directly to php output stream (same as echo)
	    $outputStream = fopen('php://output', 'w');

		// Write the CSV header based on the keys of the first element in the array, then write csv lines for each element in the array
        fputcsv($outputStream, array_keys($machines[0]));
		foreach ($machines as $row) 
		{
			fputcsv($outputStream, $row);
		}

		// Close the output stream
		fclose($outputStream);
	}
	else 
	{
		header('Content-Type: application/json');
		echo json_encode($machines);
	}

?>