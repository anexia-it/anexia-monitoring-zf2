<?php
namespace Anexia\Monitoring\Service;

use Zend\Db\Adapter\Adapter;

/**
 * Class UpService
 */
class UpService {

    /** @var Adapter */
    protected $adapter;

    /** @var string */
    protected $customDbCheckService;

    /** @var string[] */
    protected $errors = array();

    /**
     * UpService constructor.
     * @param Adapter $adapter
     * @param UpCheckServiceInterface|null $customDbCheckService
     */
    public function __construct(Adapter $adapter, UpCheckServiceInterface $customDbCheckService = null)
    {
        $this->adapter = $adapter;

        if ($customDbCheckService instanceof UpCheckServiceInterface) {
            $this->customDbCheckService = $customDbCheckService;
        }
    }

    /**
     * Check the db connection and that a select from a table returns results
     *
     * @return bool
     */
    public function checkUpStatus()
    {
        // check connection
        try {
            $this->adapter->getDriver()->getConnection()->connect();
        } catch (\Exception $e) {
            $this->errors[] = 'Database failure: Could not connect to db (error:' . $e->getMessage() . ')';
        }

        // hook for custom db checks
        if ($this->customDbCheckService) {
            $customErrors = array();
            $customCheck = $this->customDbCheckService->check($customErrors);

            if (!$customCheck || !empty($customErrors)) {
                // custom db check failed and/or returned errors
                if (empty($customErrors)) {
                    // default error message, in case custom check failed without adding information to $customErrors
                    $customErrors[] = 'ERROR';
                }

                $this->errors = array_merge($this->errors, $customErrors);
            }
        }

        if (!empty($this->errors)) {
            return false;
        }

        return true;
    }

    /**
     * @return \string[]
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return string
     */
    public function printErrors()
    {
        $errorString = '';

        if (!empty($this->errors)) {
            foreach ($this->errors as $error) {
                $errorString .= $error . "\n";
            }
        }

        return $errorString;
    }
}