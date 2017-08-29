<?php
namespace Anexia\Monitoring\Service;

use Anexia\ComposerTools\Traits\ComposerPackagistTrait;
use Zend\Version\Version;

/**
 * Class VersionService
 */
class VersionService
{
    use ComposerPackagistTrait;

    /**
     * Get environment (php and zf2) related information
     *
     * @return array
     */
    public function getRuntimeData()
    {
        return array(
            'platform' => 'php',
            'platform_version' => phpversion(),
            'framework' => 'zend',
            'framework_installed_version' => Version::VERSION,
            'framework_newest_version' => Version::getLatest()
        );
    }
}