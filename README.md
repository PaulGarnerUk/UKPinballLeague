# UKPinballLeague

This is a public repository of the UK Pinball League site, originally created and maintained by Nick Hill until 2021 where the maintenance was passed on to a new group of enthusiasts!

## Development Environment Setup

[XAMPP](https://www.apachefriends.org/index.html) is recommended for installing Apache and PHP onto the development environment. Once installed it is recommended to update the PATH to include a direct path to php.exe (by default installed to C:\xampp\php\)  

The site requires a Microsoft plugin to connect to SQL Server with PHP. This can be obtained following instructions [here](https://docs.microsoft.com/en-us/sql/connect/php/download-drivers-php-sql-server?view=sql-server-ver15) to obtain a zip file containing the Windows drivers  

Then as broadly documented [here](https://docs.microsoft.com/en-gb/sql/connect/php/loading-the-php-sql-driver?view=sql-server-ver15) 
from the zip, copy the files **php_sqlsrv_81_ts_x64.dll** and **php_pdo_sqlsrv_81_ts_x64.dll** to C:\xampp\php\ext (assuming xampp installed to default folder, and developing on Windows x64 architecture). Then edit the **php.ini** file (C:\xampp\php\php.ini) to initialise the extensions when using PHP by adding the following line (typically into the Dynamic Extensions section)  
**extension=php_sqlsrv_81_ts_x64**  
**extension=php_pdo_sqlsrv_81_ts_x64**  

The following environment variables are required to run the php site locally (and examples show setting these with Powershell). It is assumed the developer is running the site on a local instance of SQL Server, and so should be able to configure the host/username/password.  

**New-Item Env:/currentseason -Value "15"** # Current season number  
**New-Item Env:/mssqlservername -Value "dbhost.database.windows.net"** # sql server database host name  
**New-Item Env:/mssqldb -Value "dbname"** # sql db name  
**New-Item Env:/mssqluser -Value "readonlylogin"** # username for sql db  
**New-Item Env:/mssqlpassword -Value "sqlpassword"** # password for sql db  

The following and temporarily required for some legacy pages which use the old/denormalised database scheme, hosted in MySQL  
**New-Item Env:/sqlhost -Value "legacydbhost.mysql.database.azure.com"** # legacy mysql db host name   
**New-Item Env:/dbname -Value "legacydbname"** # legacy mysql db name  
**New-Item Env:/sqluser -Value "legacyuser"** # username for legacy mysql db  
**New-Item Env:/sqlpassword -Value "legacypassword"** # password for legacy mysql db  

Script can then launch the site by ensuring we are in the root folder and running php:  
**cd C:\site-folder**  
**php -S 127.0.0.1:9876**  

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
