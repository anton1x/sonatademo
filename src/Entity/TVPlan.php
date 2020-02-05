<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class TVPlan
 * @ORM\Entity()
 */
class TVPlan extends BaseProduct
{
    /**
     * @ORM\Column(type="integer")
     */
    private $channelCount = 0;

    public const type = "tv_plan";

    /**
     * @return int
     */
    public function getChannelCount():int
    {
        return $this->channelCount;
    }

    /**
     * @param int $channelCount
     */
    public function setChannelCount($channelCount): void
    {
        $this->channelCount = $channelCount;
    }


}