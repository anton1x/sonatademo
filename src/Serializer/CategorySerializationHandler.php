<?php


namespace App\Serializer;



use App\Application\Sonata\ClassificationBundle\Entity\Category;
use JMS\Serializer\Context;
use JMS\Serializer\JsonSerializationVisitor;

class CategorySerializationHandler
{
    public function serialize(JsonSerializationVisitor $visitor, $item, array $type, Context $context)
    {
        if(!$item instanceof Category)
            throw new \InvalidArgumentException('Item should be instance of Category');

        $result = [
            'id' => $item->getId(),
            'code' => $item->getCode(),
        ];

        return $result;
    }
}