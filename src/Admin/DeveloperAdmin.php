<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Developer;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\Filter\ChoiceType;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\MediaBundle\Form\Type\MediaType;

final class DeveloperAdmin extends AbstractAdmin
{

    private $isPlanned = false;

    protected $datagridValues = [
        '_page' => 1,
        '_sort_order' => 'ASC',
        '_sort_by' => 'position',
    ];

    public function __construct($code, $class, $baseControllerName, $isPlanned)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->isPlanned = $isPlanned;
        $this->baseRouteName = 'admin_app_developer';
        if ($isPlanned) {
            $this->baseRouteName .= '_planned';
            $this->baseRoutePattern = 'developer_planned';
        }
    }

    public function configure()
    {
        parent::configure();
        if ($this->isPlanned) {
            $this->classnameLabel = 'Developer Planned';
        }
    }


    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('name')
            ->add('address')
            //->add('isPlannedProject')
            //->add('apartmentsCount')
            //->add('builder')
            ;
    }



    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('move', $this->getRouterIdParameter().'/move/{position}');
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('name', null, [
                'editable' => true,
            ])
            ->add('address', null, [
                'editable' => true,
            ])
            ->add('apartmentsCount', null, [
                'editable' => true,
            ])
//            ->add('builder', null, [
//                'editable' => true,
//            ])
            ->add('_action', null, [
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                    'move' => [
                        'template' => '@PixSortableBehavior/Default/_sort.html.twig'
                    ],
                ],
            ]);

    }

    public function prePersist($object)
    {
        /**
         * @var Developer $object
         */
        $object->setIsPlannedProject($this->isPlanned);
    }

    public function createQuery($context = 'list')
    {
        $q =  parent::createQuery($context);

        if ($context == 'list') {
            $q->andWhere('o.isPlannedProject = :planned');
            $q->setParameter('planned',  $this->isPlanned);
        }

        return $q;
    }


    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->add('name')
            ->add('image', MediaType::class, [
                'context' => 'developers',
                'provider' => 'sonata.media.provider.image'
            ])
            ->add('address')
            ->add('apartmentsCount', null, [
                'required' => false,
            ])
            //->add('builder')
            ;
    }

}
