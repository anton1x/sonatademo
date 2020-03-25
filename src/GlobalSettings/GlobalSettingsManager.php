<?php


namespace App\GlobalSettings;


use App\Entity\GlobalSettings;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

final class GlobalSettingsManager
{
    public const YAML_FILENAME = 'global_settings.yaml';
    public const YAML_PATH = '/config/';
    public const CACHE_KEY = 'app.global_settings';
    /**
     * @var string
     */
    private $yamlPath;
    /**
     * @var ArrayAdapter
     */
    private $cache;
    private $settings = [];

    public function __construct(KernelInterface $kernel, CacheInterface $cache)
    {
        $this->yamlPath = $kernel->getProjectDir() . self::YAML_PATH . self::YAML_FILENAME;
        $this->cache = $cache;
    }

    public function loadOptions()
    {
        $settings = $this->cache->get(self::CACHE_KEY, function (ItemInterface $item) {
            $options = $this->loadFromFile();
            $item->set($options);
            return $options;
        });

        $this->settings = $settings;
    }

    public function getSettings()
    {
        return new GlobalSettings($this->settings);
    }

    public function setSettings(GlobalSettings $settings)
    {
        $this->settings = $settings->getSettings();
    }

    protected function reloadCache()
    {
        try {
            $this->cache->delete(self::CACHE_KEY);
        } catch (InvalidArgumentException $e) {
            return false;
        }

        $this->loadOptions();

        return true;
    }

    public function save()
    {
        $yaml = Yaml::dump($this->settings);
        $result = file_put_contents($this->yamlPath, $yaml);

        if (false !== $result) {
            $this->reloadCache();
        }

        return $result > 0;
    }

    private function loadFromFile()
    {
        $options = [];

        try {
            $content = file_get_contents($this->yamlPath);
        }
        catch (\ErrorException $exception) {
            $content = false;
        }

        if ($content !== false) {
            try {
                $options = Yaml::parse($content);
            } catch (ParseException $exception) {
                $options = [];
            }
        }

        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $options = $resolver->resolve($options);

        return $options;
    }

    private function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'cloudpayments_api_key' => 'test_api_00000000000000000000001',
            'mail_from' => 'antongaran@mail.ru',
        ]);
    }
}