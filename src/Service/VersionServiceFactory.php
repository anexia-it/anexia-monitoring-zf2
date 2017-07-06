<?php
namespace Anexia\Monitoring\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class VersionServiceFactory
 * @package Anexia\Monitoring\Service
 */
class VersionServiceFactory implements FactoryInterface
{
    /**
     * (non-PHPdoc)
     * @see \Zend\ServiceManager\FactoryInterface::createService()
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $versionService = new VersionService();
        return $versionService;
    }
}