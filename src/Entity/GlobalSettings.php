<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class GlobalSettings
 * @package App\Entity
 * @ORM\Entity(readOnly=true)
 */
class GlobalSettings
{
    private $settings = [];


    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    public function __construct($settings)
    {
        $this->settings = $settings;
    }

    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * @param array $settings
     */
    public function setSettings(array $settings): void
    {
        $this->settings = $settings;
    }
}