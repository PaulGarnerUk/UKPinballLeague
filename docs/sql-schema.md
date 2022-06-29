## Database Schema

### `Region`

| Field | Type | Nullable | Example Value | Comment |
| --- | --- | --- | --- | ---
| id | int | NO | 1 | Primary key.
| Name | nvarchar(100) | NO | 'Northern' | Region name.
| Synonym | nvarchar(10) | NO | 'n' | Very short synonym for the region. Used as alias in some queries or API requests.
| Director | nvarchar(100) | YES | 'David Dutton' | Region director.
| DirectorEmail | nvarchar(100) | YES | 'email@address.com' | Region director email address.

### `Season`

| Field | Type | Nullable | Example Value | Comment |
| --- | --- | --- | --- | ---
| id | int | NO | 1 | Primary key.
| SeasonNumber | int | NO | 15 | Incremental season number. The first season ended in 2007.
| Name | nvarchar(100) | NO | 2021/Season 15 | Name as it appears on website.
| Year | int | NO | 2021 | Year

### `LeagueMeet`

| Field | Type | Nullable | Example Value | Comment |
| --- | --- | --- | --- | ---
| id | int | NO | 1 | Primary key.
| SeasonId | int | NO | 15 | Foreign Key.
| RegionId | int | NO | 1 | Foreign Key.
| CompetitionId | int | NO | 125 | Foreign Key.
| MeetNumber | int | NO | 1 | Each region & season typically has 6 meets.
| Status | tinyint | YES | 0 | 0=Unscheduled (date not shown). 1=Planned (date shown). 2=In progress (future use). 3=Complete. 4=Cancelled. 5=Rescheduled.
| Date | datetime | YES | | Date (only, no time) of the meet.
| PracticeStart | datetime | YES | | Time (only, date insignificant) the venue opens for practice.
| PracticeEnd | datetime | YES | | Time (only, date insignificant) the practice ends.
| CompetitionStart | datetime | YES | | Time (only, date insignificant) the competition starts.
| CompetitionEnd | datetime | YES | | Time (only, date insignificant) the competition is expected to end.
| Location | nvarchar(50) | YES | Special When Lit, Salisbury | Rough geographical area. Appears on the schedule page.
| Address | nvarchar(500) | YES | Special When Lit, Stephenson Rd, Salisbury SP2 7NS | More precise address. Appears on schedule info page (for public venues only) as a link to Google Maps directions.
| Host | nvarchar(100) | YES | Tom Jones | Name of the host (or name of the venue).
| PublicVenue | bit | YES | 1 | True for public venues, false for private addresses.
| LegacyIdentifier | varchar(11) | YES | SC 14.2 | Legacy info for validating data migration.

### `Competition`

This is a table to help both LeagueMeets and LeagueFinals to join to a standard set of following schema (Competition/Machine/Score etc)

| Field | Type | Nullable | Example Value | Comment |
| --- | --- | --- | --- | ---
| id | int | NO | 131 | Primary key.
| CompetitionType | int | NO | 0 | Enumerated 0=League Meet, 1=League Final.
| Name | nvarchar(250) | YES | 2016 Irish League Meet 5 | Friendly string identifier.

### `CompetitionMachine`

List of machines expected at specific competition. Appears in schedule info page (and may have further use in future)

| Field | Type | Nullable | Example Value | Comment |
| --- | --- | --- | --- | ---
| id | int | NO | 131 | Primary key.
| CompetitionId | int | NO | 125 | Foreign Key.
| MachineId | int | NO | 101 | Foreign Key.
| Notes | nvarchar(500) | YES | 'Play extra balls.' | Machine (and competition) specific notes. 

### `CompetitionPlayer`

List of players expected at specific competition. This table will see more use once login and registration is supported. Can also be used to identify non-league players (to exclude them from calculated results)

| Field | Type | Nullable | Example Value | Comment |
| --- | --- | --- | --- | ---
| id | int | NO | 131 | Primary key.
| CompetitionId | int | NO | 125 | Foreign Key.
| PlayerId | int | NO | 634 | Foreign Key.
| ExcludeFromResults | bit | NO | | False by default. Set true to exclude player from calculated results.

### `Machine`

Machine played in the league. At the moment we aren't differentiating between (eg) Stern Pro and Premium models, but the opdbid would allow this at some future point.  May add further identifiers to this data soon, such as ipdb identifiers etc.

| Field | Type | Nullable | Example Value | Comment |
| --- | --- | --- | --- | ---
| id | int | NO | 131 | Primary key.
| Name | nvarchar(300) | NO | 'Twilight Zone' | 
| OpdbId | nvarchar(30) | YES | 'GrXzD-MjBPX' | Open pinball db identifier.

### `Player`

League player. Once login is supported this table will most likely also contain a guid to (optionally) link player to a login.

| Field | Type | Nullable | Example Value | Comment |
| --- | --- | --- | --- | ---
| id | int | NO | 131 | Primary key.
| Name | nvarchar(100) | NO | 'Paul Garner' | 
| IfpaId | int | YES | 1074 | IFPA identifier (not currently used)

### `Score`

League player. Once login is supported this table will most likely also contain a guid to (optionally) link player to a login.

| Field | Type | Nullable | Example Value | Comment |
| --- | --- | --- | --- | ---
| id | int | NO | 131 | Primary key.
| CompetitionId | int | NO | 125 | Foreign Key. Competition (league meet or league final) that score was obtained at.
| PlayerId | int | NO | 634 | Foreign Key.
| Score | bigint | YES | 123456780 |



