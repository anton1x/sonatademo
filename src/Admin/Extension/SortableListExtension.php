<?php


namespace App\Admin\Extension;

use App\Form\Type\SeoInfoType;
use Sonata\AdminBundle\Admin\AbstractAdminExtension;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

final class SortableListExtension extends AbstractAdminExtension
{
    public function configureRoutes(AdminInterface $admin, RouteCollection $collection)
    {
        if (!$collection->has('move')) {
            $collection->add('move', $admin->getRouterIdParameter().'/move/{position}');
        }

    }

    public function configureListFields(ListMapper $listMapper)
    {
        $actions = $listMapper->get('_action')->getOption('actions');

        if (!isset($actions['move'])) {
           $actions = array_merge(
               $actions,
               ['move' => [
                   'template' => '@PixSortableBehavior/Default/_sort.html.twig',
               ]]
           );
        }
        $listMapper->get('_action')->setOption('actions', $actions);
    }


}
