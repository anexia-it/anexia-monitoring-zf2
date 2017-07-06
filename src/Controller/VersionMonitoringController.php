<?php
namespace Anexia\Monitoring\Controller;

use Anexia\Monitoring\Service\AuthorizationService;
use Anexia\Monitoring\Service\VersionService;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

/**
 * Class VersionMonitoringController
 * @package Anexia\Monitoring\Controller
 */
class VersionMonitoringController extends AbstractRestfulController
{
    /**
     * Retrieve runtime and composer package version information
     *
     * @return JsonModel
     */
    public function getVersionInformationAction()
    {
        /** @var AuthorizationService $authService */
        $authService = $this->getServiceLocator()->get('Anexia\Monitoring\Service\Authorization');

        if (!$authService->checkAccessToken($this->request)) {
            // no valid access_token given as GET parameter
            $this->response->setStatusCode(401);

            return new JsonModel(
                array(
                    'code' => 'Unauthorized',
                    'message' => 'You are not authorized to do this',
                )
            );
        }

        /** @var VersionService $versionService */
        $versionService = $this->getServiceLocator()->get('Anexia\Monitoring\Service\Version');
        $runtime = $versionService->getRuntimeData();
        $modules = $versionService->getModuleData();

        return new JsonModel(
            array (
                'runtime' => $runtime,
                'modules' => $modules
            )
        );
    }
}