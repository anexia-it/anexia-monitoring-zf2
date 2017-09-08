# Anexia Monitoring

A Zend Framework 2 plugin used to monitor updates for core and composer packages. It can be also used to check
if the website is alive and working correctly.

## Installation and configuration

Install the module via composer, therefore adapt the ``require`` part of your ``composer.json``:
```
"require": {
    "anexia/zf2-monitoring": "1.2.0"
},
```

In the projects ``application.config.php`` add the new module:
```
return array(
    'modules' => array(
        'Anexia\Monitoring'
    )
);
```

In the projects ``local.php`` config file add the access token configuration:
```
return array(
    'ANX_MONITORING_ACCESS_TOKEN' => '<custom_monitoring_token>'
);
```

In the projects ``local.php`` config file add the database connection configuration:
```
return array(
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

## Usage

The module registers some custom REST endpoints which can be used for monitoring. Make sure that the
**ANX_MONITORING_ACCESS_TOKEN** is defined, since this is used for authorization. The endpoints will return a 401
HTTP_STATUS code if the token is not defined or invalid, or will return a 200 HTTP_STATUS code if everything went well.

### Version monitoring of core and composer packages

Returns all a list with platform and composer package information.

**URL:** `/anxapi/v1/modules?access_token=custom_access_token`

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
      "framework":"zend",
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

### Live monitoring

This endpoint can be used to verify if the application is alive and working correctly. It checks if the database
connection is working. It allows to register custom checks by using hooks.

**URL:** `/anxapi/v1/up?access_token=custom_access_token`

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

**Custom up check failure (without custom error message):**

Response headers (custom check failed without additional error message):
```
Status Code: 500 Internal Server Error
Access-Control-Allow-Origin: *
Access-Control-Allow-Credentials: true
Allow: GET
Content-Type: text/plain
```

Response body (containing default error message):
```
ERROR
```

**Custom up check failure (with custom error message):**

Response headers (custom check failed without additional error message):
```
Status Code: 500 Internal Server Error
Access-Control-Allow-Origin: *
Access-Control-Allow-Credentials: true
Allow: GET
Content-Type: text/plain
```

Response body (containing custom error message):
```
This is an example for a custom db check error message!
```

### Custom up check

The ``anexia/zf2-monitoring`` only checks the DB connection / DB availability.
To add further up checks a customized service can be defined. This service must implement the 
``Anexia\Monitoring\Service\UpCheckServiceInterface`` and must be available as ``Anexia\Monitoring\Service\UpCheck``.
Therefore two steps are necessary:

Add a new service class (and its factory) to the project source code tree, e.g.:
```php
<?php
// new service class /module/Application/Service/UpCheckService.php
namespace Application\Service;

use Anexia\Monitoring\Service\UpCheckServiceInterface;

class UpCheckService implements UpCheckServiceInterface
{
    /**
     * {@inheritdoc}
     */
    public function check(&$errors = array())
    {
        // add db check/validation here
        /**
         * e.g.:
         *
         * if ($success) {
         *     return true;
         * } else {
         *     $errors[] = 'Database failure: something went wrong!';
         *     return false;
         * } 
         */
    }
}
```

```php
<?php
// new service's factory class /module/Application/Service/UpCheckServiceFactory.php
namespace Application\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class UpCheckServiceFactory
 * @package Application\Service
 */
class UpCheckServiceFactory implements FactoryInterface
{
    /**
     * (non-PHPdoc)
     * @see \Zend\ServiceManager\FactoryInterface::createService()
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $upCheckService = new UpCheckService();
        return $upCheckService;
    }
}
```

Declare the new service class to be used (via its factory) as ``Anexia\Monitoring\Service\UpCheck`` in the service's
``module.config.php``, e.g.:
```php
<?php
// in module/Application/config/module.config.php
return array(
    'service_manager' => array(
        'factories' => array(
            'Anexia\Monitoring\Service\UpCheck' => 'Application\Service\UpCheckServiceFactory',
        )
    )
);
```

The customized service's ``check`` method is automatically added to the ``anexia/zf2-monitoring`` module's db check. If the
customized service's ``check`` method returns ``false`` and/or adds content to its ``$error`` array (given as method parameter by
reference), the ``anexia/zf2-monitoring`` module's up check will fail. 
If the customized service's ``check`` method returns ``false`` without giving any additional information in the ``$error`` array
(array stays empty), the response will automatically add the default error message ``ERROR`` to the response. 

## List of developers

* Alexandra Bruckner <ABruckner@anexia-it.com>, Lead developer

## Project related external resources

* [ZF2 documentation](https://framework.zend.com/manual/2.4/en/index.html)
