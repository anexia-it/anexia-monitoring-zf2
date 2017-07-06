<?php
namespace Anexia\Monitoring\Service;

use Zend\Db\Adapter\Adapter;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class UpServiceFactory
 * @package Anexia\Monitoring\Service
 */
class UpServiceFactory implements FactoryInterface
{
    /**
     * (non-PHPdoc)
     * @see \Zend\ServiceManager\FactoryInterface::createService()
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var Adapter $adapter */
        $adapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $config = $serviceLocator->get('Config');
        $tableToCheck = isset($config['ANX_MONITORING_TABLE_TO_CHECK']) ? $config['ANX_MONITORING_TABLE_TO_CHECK'] : null;
        $upService = new UpService($adapter, $tableToCheck);
        return $upService;
    }
}