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

        if ($serviceLocator->has('Anexia\Monitoring\Service\UpCheck')) {
            // add an optional custom db check service
            /** @var UpCheckServiceInterface $customDbCheckService */
            $customDbCheckService = $serviceLocator->get('Anexia\Monitoring\Service\UpCheck');
            $upService = new UpService($adapter, $customDbCheckService);
        } else {
            $upService = new UpService($adapter);
        }

        return $upService;
    }
}