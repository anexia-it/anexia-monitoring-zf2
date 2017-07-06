=== Anexia Monitoring ===
Contributors: anxabruckner
License: MIT

A Zend Framwork 2 module used to monitor updates for environment and composer packages. It can be also used to check if the website
is alive and working correctly.

== Description ==
A ZF2 module used to monitor updates for environment and composer packages. It can be also used to check if the website
is alive and working correctly.

The module registers some custom REST endpoints which can be used for monitoring. Make sure that the
**ANX_MONITORING_ACCESS_TOKEN** is defined, since this is used for authorization. The endpoints will return a 401
HTTP_STATUS code if the token is not defined or invalid, and a 200.

= Version monitoring of core, plugins and themes =

Returns all a list with platform and package information.

**Active permalinks**

	/anxapi/v1/modules/?access_token=custom_access_token

**Default**

	/?rest_route=/anxapi/v1/modules/&access_token=custom_access_token

= Live monitoring =

This endpoint can be used to verify if the application is alive and working correctly. It checks if the database
connection is working and makes a query for users. It allows to register custom check by using hooks.

**Active permalinks**

	/anxapi/v1/up/?access_token=custom_access_token

**Default**

	/?rest_route=/anxapi/v1/up/&access_token=custom_access_token

== Installation ==
In the projects local.php add the access token configuration:

	return array(
        'ANX_MONITORING_ACCESS_TOKEN' => '<custom_monitoring_token>'
    );
