# Example sql queries

Some of these may be migrated to stored procedures or table valued functions in time, but until then these may help explain the database schema somewhat.


## Adding a new league meet

Create new competition for the meet.  CompetitionType is an enumerated value. 0=LeagueMeet

```
INSERT INTO Competition (CompetitionType, Name)
VALUES (0, '2021 Northern League Meet 1'
```

Add details of the meet

```
INSERT INTO LeagueMeet (
  SeasonId, 
  RegionId, 
  CompetitionId, 
  Date,   
  PracticeStart, 
  PracticeEnd, 
  CompetitionStart, 
  CompetitionEnd, 
  MeetNumber, 
  PublicVenue, 
  Location, 
  Address,
  Host, 
  Status
)
VALUES
(
  15, -- season id
  1,  -- region id
  437, -- competition id from previous insert
  CONVERT(DATETIME, '2022-05-15 00:00:00', 120), -- date
  CONVERT(DATETIME, '2022-05-15 10:00:00', 120), -- practice start
  CONVERT(DATETIME, '2022-05-15 12:00:00', 120), -- practice end
  CONVERT(DATETIME, '2022-05-15 13:00:00', 120), -- comp start
  CONVERT(DATETIME, '2022-05-15 16:00:00', 120), -- comp end
  5, -- meet number
  0, -- public venue
  'Southport', -- Broad area
  null, -- Exact address including postcode for public venues
  'Andy Foster', -- Host
  1 -- status  1=planned 2=inprogress 3=complete 4=cancelled 5=rescheduled
) 
```

Add machines at the meet. Notes are intended to be used for player instructions regarding any machine specific quirks, such as 'play extra balls' or 'game set to 5 balls' and so on.

```
insert into CompetitionMachine (CompetitionId, MachineId, Notes) values
(437, 101, null),
```

When adding machines at meets it's useful to query by machine name, and potentially add new machines.

```
SELECT * FROM Machine WHERE Name LIKE '%Family%'
-- INSERT INTO Machine (Name, OpdbId) values ('Skill Pool', 'G4O66-MW96E')
```

## Adding scores from a league meet

Currently done from a spreadsheet containing scores like

```
DECLARE @MachineId int = 310
INSERT INTO Score (CompetitionId, PlayerId, MachineId, Score) VALUES 
(438, 280, @MachineId, 18805520), 
--etc for each player
```

## Calculate results of league meet

Once all scores are entered the following query calculates results.
This query also (dynamically) allows non-league players to be included/excluded from the calculated results.

```
DECLARE @CompetitionId INT = 498; -- competition id to calculate results for
DECLARE @ExcludeNonLeaguePlayers BIT = 1

-- Conditionally build a table variable containing excluded player ids
DECLARE @ExcludedPlayerIds TABLE (PlayerId INT)
IF (@ExcludeNonLeaguePlayers = 1) BEGIN
	INSERT INTO @ExcludedPlayerIds (PlayerId) 
	SELECT PlayerId 
	FROM CompetitionPlayer
	WHERE CompetitionPlayer.CompetitionId = @CompetitionId
	AND CompetitionPlayer.ExcludeFromResults = 1
	END

-- Calculate number of players at meet
DECLARE @TotalPlayers INT
SET @TotalPlayers = (SELECT COUNT(DISTINCT(PlayerId))
	FROM Score 
	WHERE CompetitionId = @CompetitionId
	AND PlayerId NOT IN (SELECT PlayerId FROM @ExcludedPlayerIds));

WITH MeetScores AS
(
	SELECT
	Score.PlayerId AS 'PlayerId',
	Score.MachineId AS 'MachineId',
	Score.Score,
	RANK() OVER (PARTITION BY Score.MachineId ORDER BY Score.Score DESC) AS 'Position',
	RANK() OVER (PARTITION BY Score.MachineId ORDER BY Score.Score ASC) AS 'Points'
	FROM Score
	WHERE CompetitionId = @CompetitionId
	AND Score.PlayerId NOT IN (SELECT PlayerId FROM @ExcludedPlayerIds)
),
BonusPoints AS
(
	SELECT
	TopScore.PlayerId,
	1 AS BonusPoint
	FROM MeetScores AS TopScore
	INNER JOIN MeetScores AS SecondScore ON SecondScore.MachineId = TopScore.MachineId AND SecondScore.Position = 2
	WHERE TopScore.Position = 1
	AND (TopScore.Score >= (SecondScore.Score * 2))
),
TotalPoints AS
(
	SELECT
	MeetScores.PlayerId,
	SUM(MeetScores.Points)
	+ (SELECT COALESCE(SUM(BonusPoints.BonusPoint),0) FROM BonusPoints WHERE BonusPoints.PlayerId = MeetScores.PlayerId)
	  AS TotalPoints
	FROM MeetScores
	GROUP BY MeetScores.PlayerId
),
RankedResults AS
(
	SELECT
	PlayerId AS 'PlayerId',
	TotalPoints.TotalPoints AS 'Score',
	RANK() OVER (ORDER BY (TotalPoints.TotalPoints) DESC) AS 'Position',
	CASE WHEN (((ROW_NUMBER() OVER (ORDER BY (TotalPoints.TotalPoints) ASC))-@TotalPlayers)+20) < 0 THEN 0 
	  ELSE (((ROW_NUMBER() OVER (ORDER BY (TotalPoints.TotalPoints) ASC))-@TotalPlayers)+20) 
	  END as 'Points'
	FROM TotalPoints
)

SELECT
	@CompetitionId,
	PlayerId,
	Player.Name,
	Score,
	Position,
	CASE
	    -- When two (or more) players have the same position, then sum the points and divide by the number of players
		WHEN (SELECT COUNT(*) FROM RankedResults WHERE Position = r.Position) > 1 THEN (
			SELECT CAST(SUM(RankedResults.Points) AS float) FROM RankedResults WHERE Position = r.Position ) / (SELECT COUNT(*) FROM RankedResults WHERE Position = r.Position
		) ELSE (
		    r.Points
		)
	END as 'Points'
FROM RankedResults r
INNER JOIN Player ON Player.Id = r.PlayerId
ORDER BY Score DESC, Player.Name ASC
```

## Add results
Add results from the query above, 
```
INSERT INTO Result (CompetitionId, PlayerId, Score, Position, Points) VALUES
(...etc)
```


# New Season Preparation

Start by adding a new Season  
```
INSERT INTO Season (SeasonNumber, Name, Year) VALUES (16, '2023/Season 16', 2023)
```
The app should be configured to use an environment variable named `currentseason` containing the latest season number (16 in this example). This is initialized in the `envvars.inc` include.

