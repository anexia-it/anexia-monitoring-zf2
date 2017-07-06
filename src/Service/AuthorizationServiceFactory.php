<?php
namespace Anexia\Monitoring\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AuthorizationServiceFactory
 * @package Anexia\Monitoring\Service
 */
class AuthorizationServiceFactory implements FactoryInterface
{
    /**
     * (non-PHPdoc)
     * @see \Zend\ServiceManager\FactoryInterface::createService()
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        $accessToken = isset($config['ANX_MONITORING_ACCESS_TOKEN']) ? $config['ANX_MONITORING_ACCESS_TOKEN'] : null;
        $authService = new AuthorizationService($accessToken);
        return $authService;
    }
}