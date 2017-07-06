# Anexia Monitoring

A Zend Framework 2 plugin used to monitor updates for core, plugins and themes. It can be also used to check if the website
is alive and working correctly.

## Installation and configuration

Install the module via composer, therefore adapt the "require" part of your composer.json:
```
"require": {
        ... // other required packages
        "anexia/zf2-monitoring": "1.0"
    },
```

In the projects application.config.php add the new module:
```
return array(
    'modules' => array(
        ... // other application modules
        'Anexia\Monitoring'
    )
);
```


In the projects local.php config file add the access token configuration:
```
return array(
    ... // other configurations
    'ANX_MONITORING_ACCESS_TOKEN' => '<custom_monitoring_token>'
);
```


In the projects local.php config file add the database connection configuration:
```
return array(
    ... // other configurations
    'db' => array(
        'host' => '<host>', // e.g. localhost
        'username' => '<username>',
        'password' => '<password>',
        'database' => '<database>',
        'driver' => '<driver>', // e.g. 'mysql',
        'dns' => 'mysql:dbname=<database>;host=<host>'
    )
);
```


In the projects local.php config file add the database table to be checked on live (/up) monitoring:
```
return array(
    ... // other configurations
    'ANX_MONITORING_TABLE_TO_CHECK' => '<table_name>' // 'user' by default
);
```

## Usage

The module registers some custom REST endpoints which can be used for monitoring. Make sure that the
**ANX_MONITORING_ACCESS_TOKEN** is defined, since this is used for authorization. The endpoints will return a 401
HTTP_STATUS code if the token is not defined or invalid, and a 200.

#### Version monitoring of core, plugins and themes

Returns all a list with platform and module information.

**URL:**
* Active permalinks: `/anxapi/v1/modules/?access_token=custom_access_token`
* Default: `/?rest_route=/anxapi/v1/modules/&access_token=custom_access_token`

Response headers:
```
Status Code: 200 OK
Access-Control-Allow-Origin: *
Access-Control-Allow-Credentials: true
Allow: GET
Content-Type: application/json
```

Response body:
```
{
   "runtime":{
      "platform":"php",
      "platform_version":"7.0.19",
      "framework":"zend framework 2",
      "framework_installed_version":"2.4",
      "framework_newest_version":"3.0.1"
   },
   "modules":[
      {
         "name":"package-1",
         "installed_version":"3.1.10",
         "newest_version":"3.3.2"
      },
      {
         "name":"package-2",
         "installed_version":"1.4",
         "newest_version":"1.4"
      },
      ...
   ]
}
```


#### Live monitoring

This endpoint can be used to verify if the application is alive and working correctly. It checks if the database
connection is working and makes a query for users. It allows to register custom check by using hooks.

**URL:**
* Active permalinks: `/anxapi/v1/up/?access_token=custom_access_token`
* Default: `/?rest_route=/anxapi/v1/up/&access_token=custom_access_token`

Response headers:
```
Status Code: 200 OK
Access-Control-Allow-Origin: *
Access-Control-Allow-Credentials: true
Allow: GET
Content-Type: text/plain
```

Response body:
```
OK
```


## List of developers

* Alexandra Bruckner, Lead developer

## Project related external resources

* [ZF2 documentation](https://framework.zend.com/manual/2.4/en/index.html)
