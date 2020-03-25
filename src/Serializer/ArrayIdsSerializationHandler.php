<?php


namespace App\Serializer;



use JMS\Serializer\Context;
use JMS\Serializer\JsonSerializationVisitor;

class ArrayIdsSerializationHandler
{
    public function serialize(JsonSerializationVisitor $visitor, $item, array $type, Context $context)
    {
        if(!is_iterable($item))
            throw new \InvalidArgumentException('Item should be iterable');

        $result = [];

        foreach ($item as $element){
            if(method_exists($element, 'getId'))
                $result[] = $element->getId();
            else
                throw new \InvalidArgumentException('Elements should have getId method');
        }

        return $result;
    }
}