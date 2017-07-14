<?php
namespace Anexia\Monitoring\Controller;

use Anexia\Monitoring\Service\AuthorizationService;
use Anexia\Monitoring\Service\UpService;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

/**
 * Class UpMonitoringController
 * @package Anexia\Monitoring\Controller
 */
class UpMonitoringController extends AbstractRestfulController
{
    /**
     * Check the database connection and return 'OK' on success
     */
    public function checkUpStatusAction()
    {
        $this->response->getHeaders()->addHeaders(array('Content-Type' => 'text/plain'));

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

        /** @var UpService $upService */
        $upService = $this->getServiceLocator()->get('Anexia\Monitoring\Service\Up');
        if (!$upService->checkUpStatus()) {
            // up check was not successful
            $this->response->setStatusCode(500);
            $this->response->setContent($upService->printErrors());
            return $this->response;
        }

        $this->response->setContent('OK');
        return $this->response;
    }
}