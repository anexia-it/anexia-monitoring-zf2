<?php
return array(
    'service_manager' => array (
        'factories' => array(
            'Anexia\Monitoring\Service\Authorization' => 'Anexia\Monitoring\Service\AuthorizationServiceFactory',
            'Anexia\Monitoring\Service\Version' => 'Anexia\Monitoring\Service\VersionServiceFactory',
            'Anexia\Monitoring\Service\Up' => 'Anexia\Monitoring\Service\UpServiceFactory',
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
        )
    ),
    'controllers' => array (
        'invokables' => array (
            'Anexia\Monitoring\Controller\VersionMonitoring' => 'Anexia\Monitoring\Controller\VersionMonitoringController',
            'Anexia\Monitoring\Controller\UpMonitoring' => 'Anexia\Monitoring\Controller\UpMonitoringController'
        )
    ),

    'router' => array (
        'routes' => array (
            'monitoring' => array (
                'type' => 'Literal',
                'options' => array (
                    'route' => '/anxapi/v1/modules',
                    'defaults' => array (
                        'controller' => 'Anexia\Monitoring\Controller\VersionMonitoring',
                        'action' => 'getVersionInformation'
                    )
                )
            ),
            'up' => array (
                'type' => 'Literal',
                'options' => array (
                    'route' => '/anxapi/v1/up',
                    'defaults' => array (
                        'controller' => 'Anexia\Monitoring\Controller\UpMonitoring',
                        'action' => 'checkUpStatus'
                    )
                )
            ),
        )
    ),

    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    )
);