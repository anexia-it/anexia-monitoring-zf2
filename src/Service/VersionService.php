<?php
namespace Anexia\Monitoring\Service;

use Composer\Composer;
use Composer\Semver\VersionParser;
use Zend\Version\Version;

/**
 * Class VersionService
 */
class VersionService {

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

    /**
     * Get information for composer installed packages (currently installed version and latest stable version)
     *
     * @return array
     */
    public function getComposerPackageData()
    {
        $moduleVersions = array();

        $installedJsonFile = getcwd() . '/vendor/composer/installed.json';
        $packages = json_decode(file_get_contents($installedJsonFile));

        if (count($packages) > 0) {
            foreach ($packages as $package) {
                $name = $package->name;
                $latestStableVersNo = '';

                /**
                 * get latest stable version number
                 */
                // get version information from packagist
                $packagistUrl = 'https://packagist.org/packages/' . $name . '.json';

                try {
                    $packagistInfo = json_decode(file_get_contents($packagistUrl));
                    $versions = $packagistInfo->package->versions;
                } catch (\Exception $e) {
                    $versions = array();
                }

                if (count($versions) > 0) {
                    $latestStableNormVersNo = '';
                    foreach ($versions as $versionData) {
                        $versionNo = $versionData->version;
                        $normVersNo = $versionData->version_normalized;
                        $stability = VersionParser::normalizeStability(VersionParser::parseStability($versionNo));

                        // only use stable version numbers
                        if ($stability === 'stable' && version_compare($normVersNo, $latestStableNormVersNo) >= 0) {
                            $latestStableVersNo = $versionNo;
                            $latestStableNormVersNo = $normVersNo;
                        }
                    }
                }

                if ($latestStableVersNo === '') {
                    // no stable version number was found on packagist
                    $latestStableVersNo = 'unknown';
                }

                /**
                 * prepare result
                 */
                $moduleVersions[] = array(
                    'name' => $name,
                    'installed_version' => $package->version,
                    'newest_version' => $latestStableVersNo
                );
            }
        }

        return $moduleVersions;
    }
}