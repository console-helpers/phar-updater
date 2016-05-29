<?php
/**
 * Humbug
 *
 * @category   Humbug
 * @package    Humbug
 * @copyright  Copyright (c) 2015 Pádraic Brady (http://blog.astrumfutura.com)
 * @license    https://github.com/padraic/phar-updater/blob/master/LICENSE New BSD License
 *
 * This class is partially patterned after Composer's version parser.
 */

namespace Humbug\SelfUpdate;

use Humbug\FileGetContents;

class FileDownloader
{

    private $curlOptions = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_FAILONERROR => true,

        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_SSL_VERIFYPEER => true,
    );

    /**
     * Creates file downloader instance.
     */
    public function __construct()
    {
        $cafile = FileGetContents::getSystemCaRootBundlePath();

        if (is_dir($cafile)) {
            $this->curlOptions[CURLOPT_CAPATH] = $cafile;
        } elseif ($cafile) {
            $this->curlOptions[CURLOPT_CAINFO] = $cafile;
        } else {
            throw new \RuntimeException('A valid cafile could not be located locally.');
        }
    }

    /**
     * Downloads the file.
     *
     * @param string $url Url.
     * @return string
     */
    public function download($url)
    {
        $resource = curl_init($url);

        foreach ($this->curlOptions as $optionName => $optionValue) {
            curl_setopt($resource, $optionName, $optionValue);
        }

        $result = curl_exec($resource);
        curl_close($resource);

        return $result;
    }

}
