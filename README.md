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

The following are temporarily required for some legacy pages which use the old/denormalised database scheme, hosted in MySQL  
**New-Item Env:/sqlhost -Value "legacydbhost.mysql.database.azure.com"** # legacy mysql db host name   
**New-Item Env:/dbname -Value "legacydbname"** # legacy mysql db name  
**New-Item Env:/sqluser -Value "legacyuser"** # username for legacy mysql db  
**New-Item Env:/sqlpassword -Value "legacypassword"** # password for legacy mysql db  

Script can then launch the site by ensuring we are in the root folder and running php:  
**cd C:\site-folder**  
**php -S 127.0.0.1:9876**  

### Database info

Microsoft SQL Server is used in production. [Sql Server Express](https://www.microsoft.com/en-gb/sql-server/sql-server-downloads) is suitable for local (and free) hosting in a development environment.
The SQL database is backed up infrequently and stored to the repository in bacpac format. Please DO NOT submit database changes (either schema or data) to the repository.

Database schema is documented [here](docs/sql-schema.md)

At the time of writing, maintenance on the database is performed manually. Example queries can be seen [here](docs/sql-queries.md)

