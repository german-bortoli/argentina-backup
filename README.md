# argentina-backup

Create mysql backups and store it into your local server, google drive and s3.

## TODO:
 - File per database implementation.
 - Fix compression, for now only supports gzip.
 - Add unit testing.
 - Add assets backup tool also.
 
 
## Installation

1) Clone this repository.
2) Copy env_default to .env, and then configure it.
3) Run composer install.

## Usage

 Run on your terminal 

    php run.php bk
    
## Upload to S3

 - Get amazon s3 access keys (iam-roles).
 - Configure your env variables and you are ready to go.
 
 ```$xslt
BACKUP_STORAGE: s3     
AWS_KEY="simple-key"
AWS_SECRET="mysecret-key"
AWS_REGION="some-aws-region"
AWS_BUCKET="your-awesome-bucket-name"
```
 
## Upload to Google Drive


- Get google drive keys, you can follow this guide: https://gist.github.com/ivanvermeyen/cc7c59c185daad9d4e7cb8c661d7b89b
- Configure your env variables and you are ready to go.

```$xslt
GOOGLE_CLIENT_ID="sample-google-client-id"
GOOGLE_SECRET_KEY="sample-google-secret-key"
GOOGLE_REFRESH_TOKEN="sample-google-refresh-token"
# OPTIONAL FOLDER ID
GOOGLE_FOLDER_ID="sample-google-folder-id"
```
 
## Crontab

You can add this script as a cronjob, for example, if you want to backup every day at 2am, just run in your terminal `crontab -e` and append the line below:

```$xslt
0 2 * * * php /home/ubuntu/argentina-backup/run.php bk
``` 

