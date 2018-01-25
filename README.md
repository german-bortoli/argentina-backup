# argentina-backup

Create mysql backups and store it anywhere

## TODO:
 - File per database implementation.
 - Fix compression, for now only supports gzip.
 - Add unit testing.
 - Add assets backup tool also.
 - Add google drive file adapter.
 
 
## Installation

1) Clone this repository

2) Copy env_default to .env, and then configure it

3) Run composer install

## Usage

 Run on your terminal 

    php run.php bk
    
## Upload to S3

 - First configure your env variables and you are ready to go.
 
 ```$xslt
BACKUP_STORAGE: s3     
AWS_KEY="simple-key"
AWS_SECRET="mysecret-key"
AWS_REGION="some-aws-region"
AWS_BUCKET="your-awesome-bucket-name"
```
 
 
## Crontab

You can add this script as a cronjob, for example, if you want to backup every day at 2am, just run in your terminal `crontab -e` and append the line below:

```$xslt
0 2 * * * php /home/ubuntu/argentina-backup/run.php bk
``` 

