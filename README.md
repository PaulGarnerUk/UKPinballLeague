# UKPinballLeague

This is a public repository of the UK Pinball League site, originally created and maintained by Nick Hill until 2021 where the maintenance was passed on to a new group of enthusiasts!

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
| Status | tinyint | YES | 0 | Future use. Eg 0=Completed Meet, 1=Abandoned/cancelled, 2=Planned/future.
| Date | datetime | YES | | Date (only, no time) of the meet.
| PracticeStart | datetime | YES | | Time (only, no date) the venue opens for practice.
| PracticeEnd | datetime | YES | | Time (only, no date) the practice ends.
| CompetitionStart | datetime | YES | | Time (only, no date) the competition starts.
| CompetitionEnd | datetime | YES | | Time (only, no date) the competition is expected to end.
| Address | nvarchar(500) | YES | Leyland, Lancashire, PR7 | General address for private hosts (or more specific address for public venues).
| Host | nvarchar(100) | YES | David Dutton | Name of the host (or name of the venue).
| PublicVenue | bit | YES | 1 | True for public venues, false for private addresses.
| LegacyIdentifier | varchar(11) | YES | SC 14.2 | Legacy info for validating data migration.

### `Competition`

This is a table to help both LeagueMeets and LeagueFinals to join to a standard set of following schema (Competition/Machine/Score etc)

| Field | Type | Nullable | Example Value | Comment |
| --- | --- | --- | --- | ---
| Id | int | NO | 131 | Primary key.
| CompetitionType | int | NO | 0 | Enumerated 0=League Meet, 1=League Final.
| Name | nvarchar(250) | YES | 2016 Irish League Meet 5 | Friendly string identifier.

.. to be continued
