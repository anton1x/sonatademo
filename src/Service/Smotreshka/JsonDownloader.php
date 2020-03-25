<?php


namespace App\Service\Smotreshka;


use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class JsonDownloader
{
    private $serviceUrl;
    private $backupFileWay;

    public function __construct($serviceUrl, $backupFileWay)
    {
        $this->serviceUrl = $serviceUrl;
        $this->backupFileWay = $backupFileWay;
    }

    public function getData()
    {
        $decoded = $this->loadFile();
        $decoded = $this->processArray($decoded);

        return $decoded;
    }

    protected function loadFile()
    {
        $decoded = $this->downloadAndDecode($this->serviceUrl);

        if (!$decoded) {
            $decoded = $this->downloadAndDecode($this->getBackupFileWay());
        }

        if (!$decoded)
            throw new FileException();

        return $decoded;
    }

    protected function downloadAndDecode($path, $backupContent = false)
    {
        try {
            $json = file_get_contents($path);
            $decoded = json_decode($json, true);
        } catch (\ErrorException $exception) {
            $decoded = false;
        }

        if ($decoded && $backupContent) {
            $this->saveFileContent($json);
        }

        return $decoded;
    }


    protected function getBackupFileWay()
    {
        return $this->backupFileWay;
    }

    protected function saveFileContent($json)
    {
        file_put_contents($this->getBackupFileWay(), $json);
    }


    protected function processArray($array)
    {
        $result = [];
        foreach ($array as $item) {
            if (null === $item['id'])
                continue;

            $item['channels'] = array_filter($item['channels'], function ($item) {
                return $this->shouldAddChannel($item);
            });

            $result [$item['id']] = $item['channels'];
        }
        return $result;
    }

    private function shouldAddChannel(array $channel): bool
    {
        if (isset($channel['regions']) && !in_array('ru.central.moscow-obl', $channel['regions'])) {
            return false;
        }
        return true;
    }
}