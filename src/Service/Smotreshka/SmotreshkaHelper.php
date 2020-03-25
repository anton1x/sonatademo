<?php


namespace App\Service\Smotreshka;


use App\Entity\TVPlan;
use App\Repository\TVPlanRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\Adapter\ApcuAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Adapter\MemcachedAdapter;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class SmotreshkaHelper
{
    /**
     * @var AbstractAdapter
     */
    private $cache;

    private const SMOTRESHKA_CACHE_PREFIX = 'smotreshka_info_';
    /**
     * @var TVPlanRepository
     */
    private $repo;

    private $map = [];
    /**
     * @var JsonDownloader
     */
    private $downloader;

    private function getKey($signature)
    {
        return static::SMOTRESHKA_CACHE_PREFIX . $signature;
    }

    private function getKeyForItem(int $id)
    {
        return $this->getKey('tvplan_' . $id);
    }

    public function __construct(JsonDownloader $downloader, CacheInterface $cache, EntityManagerInterface $entityManager)
    {

        $this->repo = $entityManager->getRepository(TVPlan::class);
        $this->map = $this->repo->getSmotreshkaIdList();
        $this->downloader = $downloader;
        $this->cache = $cache;
    }

    public function loadInfo()
    {
        return $this->cache->get($this->getKey('list'), function (ItemInterface $item) {
            $item->expiresAt(new \DateTime("+1 day"));
            $newData = $this->processData($this->downloader->getData());
            $item->set($newData);
            return $newData;
        });
    }

    public function clearCache()
    {
        $this->cache->delete($this->getKey('list'));
    }

    protected function processData($data)
    {
        $result = [];
        foreach ($this->map as $innerId => $smotreshkaId) {
            $result[$innerId] = $data[$smotreshkaId] ?? [];
        }
        return $result;
    }

    public function getFormattedInfo(TVPlan $plan)
    {
        $channels = $this->loadInfo()[$plan->getId()] ?? [];

        $result = [
            'name' => $plan->getTitle(),
            'channels' => array_values($channels),
        ];

        return $result;
    }

}