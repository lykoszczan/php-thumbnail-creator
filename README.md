﻿php-thumbnail-creator
=======================

## Configuration

Make directory `app/www/configs`. Put there your configuration for services. The name follows the formula FileUploader**YourServiceName**.json

### Dropbox config example

Filename: FileUploaderDropbox.json

Content:

```
{
  "token": "your token from dropboxapi"
}
```

### Aws S3 config example

Filename: FileUploaderAwsS3.json

Content:

```
{
  "region": "aws region",
  "key": "aws key",
  "secret": "aws secret"
}
```


## How to run

- Ensure that you have services configs
- Open php-thumbnail-creator directory
- Run `composer install`
- To be sure you all done correctly, run tests
- Ensure that you have docker and docker-compose
- Run `docker-compose build`
- Run `docker-compose up -d`

## How to use

- Open 127.0.0.1:80 in your browser
- Upload an image, select service to save thumbnail and put directory name or leave it empty depending on the selected
  service

![Index](https://github.com/lykoszczan/php-thumbnail-creator/blob/dev/screenshots/index.png?raw=true)

- If everything was correctly, you should see your thumbnail

![Thumbnail](https://github.com/lykoszczan/php-thumbnail-creator/blob/dev/screenshots/thumbnail.png?raw=true)

## Limitations
- If you want to upload to Aws s3, bucket name cannot be empty. You should create your bucket firstly.
- For each thumbnail stored on hard drive, parent directory is `userFiles`. If you specify directory name, folder will be created in `userFiles` and thumbnail will be saved there   
