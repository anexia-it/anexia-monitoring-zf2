<?php
namespace Anexia\Monitoring\Service;

use Composer\Composer;
use Zend\Db\Adapter\Adapter;

/**
 * Class UpService
 */
class UpService {

    /** string */
    const DEFAULT_TABLE_TO_CHECK = 'user';

    /** @var Adapter */
    protected $adapter;

    /** @var string */
    protected $tableToCheck = self::DEFAULT_TABLE_TO_CHECK;

    /** @var string[] */
    protected $errors = array();

    /**
     * UpService constructor.
     * @param Adapter $adapter
     * @param string|null $tableToCheck
     */
    public function __construct(Adapter $adapter, $tableToCheck = null)
    {
        $this->adapter = $adapter;

        if (!empty($tableToCheck)) {
            $this->tableToCheck = $tableToCheck;
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

        // check table select
        try {
            $result = $this->adapter->query('SELECT * FROM ' . $this->tableToCheck, Adapter::QUERY_MODE_EXECUTE);

            if ($result->count() < 1) {
                $this->errors[] = 'Database failure: Table "' . $this->tableToCheck . '" is empty';
            }
        } catch (\Exception $e) {
            $this->errors[] = 'Database failure: Could not select from table "' . $this->tableToCheck
                . '" (error: ' . $e->getMessage() . ')';
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