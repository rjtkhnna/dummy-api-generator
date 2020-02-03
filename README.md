# dummy-api-generator
This project enables developer use custom run-time REST APIs for their projects. The APIs are created on the fly

# Installation
The project is developed on LAMP stack and works with PHP 5.6 and above.
Please follow the below steps to download and get running.

The project utilizes default JWT based Authentication. For a new setup, kindly download the Firebase JWT Library
In your terminal, run the following command from the root of your project's directory:

`$ composer require firebase/php-jwt`

This will donwload the php-jwt library into a vendor folder.
You can require the php-jwt library to encode and decode JWT tokens using the following code:

``` php
require "vendor/autoload.php";
use \Firebase\JWT\JWT;
```
Also, user can disable the authentication from the configuration. In this case the APIs will be open.

Rename the default target data-file from `db.sample.json` to `<filename.json>`
The same should be updated in the configuration file.
Apache/API simulator like POSTMAN should have write persmission on the file.

# Methods Supported
The API generator supports following methods - 
- GET
- POST
- PUT
- DELETE

# URL formats
The APIs can be accessed over `*Hostname*/api.php/*endpoint*`
For Login - `*Hostname*/login.php` with valid uname and upwd. The token which will be received has to be used for any new endpoint call. The token will be passed in the Authorization Header without any Bearer/Auth tag.
