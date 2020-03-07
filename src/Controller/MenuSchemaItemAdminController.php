<?php

declare(strict_types=1);

namespace App\Controller;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use Mev\SortableTreeBundle\Controller\SortableTreeController;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

final class MenuSchemaItemAdminController extends CRUDController
{
    public function upAction(Request $request)
    {
        $object = $this->admin->getSubject();
        $entity = \Doctrine\Common\Util\ClassUtils::getClass($object);
        $id = $object->getId();

        $repo = $this->getDoctrine()
            ->getManager()
            ->getRepository($entity);

        $subject = $repo->findOneById($id);

        if ($subject->getParent())
        {
            $repo->moveUp($subject);
        }

        return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }
    public function downAction(Request $request)
    {
        $object = $this->admin->getSubject();
        $entity = \Doctrine\Common\Util\ClassUtils::getClass($object);
        $id = $object->getId();

        /**
         * @var NestedTreeRepository $repo
         */
        $repo = $this->getDoctrine()
            ->getManager()
            ->getRepository($entity);


        $subject = $repo->findOneById($id);

        if ($subject->getParent())
        {
            $repo->moveDown($subject);
        }

        return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }

}
