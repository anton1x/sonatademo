<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class TVPlan
 * @ORM\Entity()
 */
class AdditionalServicePlan extends BaseProduct
{

    public const type = "additional_service_plan";
    const ROOT_CATEGORIES = ['additional'];


}