<?php
namespace Anexia\Monitoring\Service;

/**
 * Class AuthorizationService
 */
class AuthorizationService
{
    protected $accessToken;

    /**
     * AuthorizationService constructor.
     * @param $accessToken
     */
    public function __construct($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * Simple token based authorization check
     *
     * @param $request
     * @return bool
     */
    public function checkAccessToken($request) {
        $params = $request->getQuery();
        // Access token must be in GET params
        if (!isset($params['access_token'])) {
            return false;
        }
        // Access token must be configured
        if (!$this->accessToken) {
            return false;
        }
        // Check if access token is correct
        if ($params['access_token'] !== $this->accessToken) {
            return false;
        }
        return true;
    }
}