# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Development Commands

### Local Development Server
```bash
php -S 127.0.0.1:9876
```
Run from the root folder to start the local development server.

### Environment Setup
Set these environment variables before running locally:
```powershell
New-Item Env:/currentseason -Value "15"
New-Item Env:/mssqlservername -Value "dbhost.database.windows.net"
New-Item Env:/mssqldb -Value "dbname"
New-Item Env:/mssqluser -Value "readonlylogin"
New-Item Env:/mssqlpassword -Value "sqlpassword"

# Legacy MySQL variables (for some legacy pages)
New-Item Env:/sqlhost -Value "legacydbhost.mysql.database.azure.com"
New-Item Env:/dbname -Value "legacydbname"
New-Item Env:/sqluser -Value "legacyuser"
New-Item Env:/sqlpassword -Value "legacypassword"
```

## Architecture Overview

### Technology Stack
- **Backend**: PHP with Microsoft SQL Server
- **Legacy Database**: MySQL (being phased out)
- **Frontend**: Plain HTML/CSS/JavaScript with responsive design
- **Server**: XAMPP for local development, Azure App Service for production

### Application Structure

**Core Directories:**
- `includes/` - Shared PHP includes (database connections, headers, environment variables)
- `api/` - API endpoints returning JSON/CSV data
- `functions/` - Business logic functions for league/region/season info
- `css/` - Stylesheets including responsive menu and UI slider components
- `js/` - JavaScript libraries (jQuery, slick nav, nouislider)
- `docs/` - Database schema and SQL query documentation

**Key Components:**
- `includes/envvars.inc` - Environment variable management with failover support
- `includes/sql.inc` - SQL Server database connection
- `includes/header.inc` - Common page header and navigation
- `api/league_meets.php` - Primary API for league meet data

### Database Architecture

**Primary Database (Microsoft SQL Server):**
- Normalized relational schema
- Core entities: Season, Region, Player, Machine, Competition, Score, Result
- Supports league meets, regional finals, and national finals
- Foreign key relationships maintain data integrity

**Legacy Database (MySQL):**
- Denormalized schema (being phased out)
- Only used by some legacy pages
- Migration to SQL Server in progress

### Key Business Logic

**League System:**
- Multi-regional structure (8 regions: South West, Midlands, London & South East, Northern, Scottish, Irish, East Anglia, South Wales)
- Seasonal competitions with 6 meets per region
- Points-based ranking system with bonus points for dominant scores (2x second place)
- Regional finals and national finals structure

**Data Flow:**
1. Scores entered manually via SQL queries (from spreadsheets)
2. Results calculated using complex ranking algorithms
3. Points awarded based on finishing position (20 points for first place, decreasing)
4. Season standings aggregated across all meets

## Code Style

- **Brace style**: Allman style — opening and closing braces on their own lines
- **Horizontal alignment**: Do not align code elements horizontally with tabs or spaces (proportional fonts are used)

## Development Notes

### Database Connection Pattern
The application uses a failover environment system controlled by the `APP_ENV` environment variable. When set to anything other than 'primary', it prefixes database connection variables to allow seamless failover.

### SQL Server PHP Extensions Required
- `php_sqlsrv_81_ts_x64.dll`
- `php_pdo_sqlsrv_81_ts_x64.dll`

### Common Development Tasks
- Adding new league meets: Use SQL templates in `docs/sql-queries.md`
- Score entry: Manual SQL insertion from spreadsheet data
- Results calculation: Complex SQL query with ranking and bonus point logic
- New season setup: Add Season record and update `currentseason` environment variable

### API Endpoints
- `api/league_meets.php` - League meet data with filtering by season/region
- `api/machines.php` - Machine information
- `api/regions.php` - Region data

All APIs support both JSON and CSV output formats via `output` parameter.