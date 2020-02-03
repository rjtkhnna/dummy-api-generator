<?php 
header('Content-Type: application/json');
$config_json = '{
    "auth-enabled": true,
    "data-file": "db.json",
    "root-path": "/Users/u261720/webroot/dummy-api-generator/",
    "jwt":{
      "secret-key":"APQWHJJKASDJJH989A86A",
      "issuer-claim":"",
      "audience-claim":"",
      "issue-date-claim":"",
      "pre-issue-time":10,
      "post-issue-time":3600,
      "validate-url":"http://localhost/dummy-api-generator/validate.php"
    },
    "creds":{
      "uname": "test-user",
      "upwd": "password12",
      "uemail": "demouser@demo.com",
      "ufname": "test",
      "ulname": "user"
    }
  }';
return $config_json;  
?>