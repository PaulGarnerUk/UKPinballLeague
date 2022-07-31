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

Once all scores are entered the following query calculates results. Note however that ties are not calculated correctly in this query at the moment (two way ties add 0.5 points, three way ties add 0.3 points etc.)

```
DECLARE @TotalPlayers INT = 11; -- total number of players at the meet
DECLARE @CompetitionId INT = 452; -- competition id to calculate results for

WITH MeetScores AS
(
	SELECT
	Player.Id AS 'PlayerId',
	Player.Name AS 'Player',
	Machine.Id AS 'MachineId',
	Machine.Name AS 'Machine',
	Score.Score,
	RANK() OVER (PARTITION BY Score.MachineId ORDER BY Score.Score DESC) AS 'Position',
	RANK() OVER (PARTITION BY Score.MachineId ORDER BY Score.Score ASC) AS 'Points'
	FROM Score
	INNER JOIN Player ON Player.Id = Score.PlayerId
	INNER JOIN Machine ON Machine.Id = Score.MachineId
	WHERE CompetitionId = @CompetitionId
),
FirstPlaceScores AS
(
	SELECT
	PlayerId,
	MachineId,
	Score
	FROM MeetScores
	WHERE MeetScores.Position = 1
),
SecondPlaceScores AS
(
	SELECT
	PlayerId,
	MachineId,
	Score
	FROM MeetScores
	WHERE MeetScores.Position = 2
),
BonusPoints AS
(
	SELECT
	FirstPlaceScores.PlayerId,
	COUNT(FirstPlaceScores.PlayerId) AS Bonus
	FROM FirstPlaceScores
	INNER JOIN SecondPlaceScores ON SecondPlaceScores.MachineId = FirstPlaceScores.MachineId
	WHERE FirstPlaceScores.Score >= (SecondPlaceScores.Score * 2)
	GROUP BY FirstPlaceScores.PlayerId
),
TotalPoints AS
(
	SELECT
	PlayerId,
	SUM(Points) AS TotalPoints
	FROM MeetScores
	INNER JOIN Player on Player.Id = PlayerId
	GROUP BY MeetScores.PlayerId
)
SELECT
@CompetitionId AS CompetitionId, --Competition.id,
Player.Id AS PlayerId,
Player.Name,
TotalPoints.TotalPoints + COALESCE(BonusPoints.Bonus, 0) AS 'Score',
RANK() OVER (ORDER BY (TotalPoints.TotalPoints + COALESCE(BonusPoints.Bonus, 0)) DESC) AS 'Position',
CASE WHEN (((RANK() OVER (ORDER BY (TotalPoints.TotalPoints + COALESCE(BonusPoints.Bonus, 0)) ASC))-@TotalPlayers)+20) < 0 THEN 0 ELSE (((RANK() OVER (ORDER BY (TotalPoints.TotalPoints + COALESCE(BonusPoints.Bonus, 0)) ASC))-@TotalPlayers)+20) END as 'Points'
FROM TotalPoints
LEFT OUTER JOIN BonusPoints on BonusPoints.PlayerId = TotalPoints.PlayerId
INNER JOIN Player on Player.Id = TotalPoints.PlayerId
ORDER BY (TotalPoints.TotalPoints + COALESCE(BonusPoints.Bonus, 0)) DESC
```

## Add results
Add results from the query above, being mindful that the query above does not handle ties very well and may need manually correcting. (Once results are calculated entirely in software this can be fixed)
```
insert into Result (CompetitionId, PlayerId, Score, Position, Points) values
(...etc)
```
