<?php

namespace App\EventListener;

use App\Entity\Address;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use App\Application\Sonata\MediaBundle\Entity\Media;

class MediaRemoveEventListener
{

    private $imageRelatedEntities;
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
        $this->imageRelatedEntities = new ArrayCollection();
        $this->addRelatedEntity(Address::class);
    }

    public function addRelatedEntity($entityClassname)
    {
        $this->imageRelatedEntities->add($entityClassname);
    }

    private function nullifyImage($entity, $field, $strategy)
    {

    }

    private function handleRemove(Media $media)
    {
        foreach ($this->imageRelatedEntities as $entityClassname){
            $metadata = $this->em->getClassMetadata($entityClassname);
            $associations = $metadata->getAssociationsByTargetClass(Media::class);
            if(0 === count($associations))
                return;
            $fieldList = array_keys($associations);
            $repo = $this->em->getRepository($entityClassname);
            $findCriteria = array_combine($fieldList, array_fill(0, count($fieldList), $media));
            dump($findCriteria);
            $itemsToNullifyImage = $repo->findBy(
                $findCriteria
            );
            dump($itemsToNullifyImage);
            exit('huy');
        }
    }

    public function preRemove(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();

        if(!$entity instanceof Media)
            return;

        $this->handleRemove($entity);

    }

}