<?php
	header('Content-Type: application/csv');
	header('Content-Disposition: attachment; filename="machines.csv"');

	include("../includes/sql.inc"); 

	$tsql="
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
FROM AverageScore
INNER JOIN HighScore ON HighScore.MachineId = AverageScore.MachineId
INNER JOIN Machine ON Machine.Id = AverageScore.MachineId
INNER JOIN Score ON Score.MachineId = Machine.Id AND Score.Score = HighScore.Score
INNER JOIN Player ON Player.Id = Score.PlayerId
INNER JOIN Competition on Competition.Id = Score.CompetitionId
ORDER BY MachineName
";

	$result = sqlsrv_query($sqlConnection, $tsql);
	if ($result == FALSE)
	{
		echo "query borken.";
	}

	echo "machine_name, machine_id, average_score, high_score, high_score_player_id, high_score_player_name, high_score_competition_id, high_score_competition_name\n";
	while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) 
	{
		$machineId = $row['MachineId'];
		$machineName = $row['MachineName'];
		$averageScore = $row['AverageScore'];
		$highScore = $row['HighScore'];
		$highScorePlayerId = $row['HighScorePlayerId'];
		$highScorePlayerName = $row['HighScorePlayerName'];
		$highScoreCompetitionId = $row['HighScoreCompetitionId'];
		$highScoreCompetitionName = $row['HighScoreCompetitionName'];

		echo "$machineName, $machineId, $averageScore, $highScore, $highScorePlayerId, $highScorePlayerName, $highScoreCompetitionId, $highScoreCompetitionName\n";
	}
?>