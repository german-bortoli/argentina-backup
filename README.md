# argentina-backup

IMPORTANT: In development stage.

For now is a mysqldump wrapper with backup rotator, it can store X quantity of backups, the main idea is to refactor the code, add more cool features, like event oriented (to send notifications for example), add services (like cloud backuping), add multiple server supports, add multiple environment support, etc.

It aims to be a *PHP* alternative for *Holland Backup*

## TODO:
 - File per database implementation.
 - Fix compression, for now only supports gzip.
 - Add unit testing.
 - Add assets backup tool also.
 
 
## Installation

1) Clone this repository

2) Copy env_default to .env, and then configure it

3) Run composer install

## Usage

 Run on your terminal 

    php run.php bk


You can also add it as a crontab entry.

