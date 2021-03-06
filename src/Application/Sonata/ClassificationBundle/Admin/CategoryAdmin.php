<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Application\Sonata\ClassificationBundle\Admin;

use App\Application\Sonata\ClassificationBundle\Controller\CategoryAdminController;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\ClassificationBundle\Admin\ContextAwareAdmin;
use Sonata\ClassificationBundle\Form\Type\CategorySelectorType;
use Sonata\ClassificationBundle\Model\ContextManagerInterface;
use Sonata\MediaBundle\Model\MediaInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Valid;

class CategoryAdmin extends ContextAwareAdmin
{
    protected $classnameLabel = 'Category';

    // NEXT_MAJOR: remove this override
    protected $formOptions = [
        'cascade_validation' => true,
    ];

    public function configureRoutes(RouteCollection $routes)
    {
        $routes->add('tree', 'tree');
    }

    public function getFormBuilder()
    {
        // NEXT_MAJOR: set constraints unconditionally
        if (isset($this->formOptions['cascade_validation'])) {
            unset($this->formOptions['cascade_validation']);
            $this->formOptions['constraints'][] = new Valid();
        } else {
            @trigger_error(<<<'EOT'
Unsetting cascade_validation is deprecated since 3.2, and will give an error in 4.0.
Override getFormBuilder() and remove the "Valid" constraint instead.
EOT
            , E_USER_DEPRECATED);
        }

        return parent::getFormBuilder();
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('group_general', ['class' => 'col-md-6'])
                ->add('name')
                ->add('code', TextType::class, [
                    'label' => 'Символьный код',
                ])
                ->add('description', TextareaType::class, [
                    'required' => false,
                ])
        ;

        if ($this->hasSubject()) {
            if (null !== $this->getSubject()->getParent() || null === $this->getSubject()->getId()) { // root category cannot have a parent
                $formMapper
                    ->add('parent', CategorySelectorType::class, [
                        'category' => $this->getSubject() ?: null,
                        'model_manager' => $this->getModelManager(),
                        'class' => $this->getClass(),
                        'required' => false,
                        'context' => $this->getSubject()->getContext(),
                    ])
                ;
            }
        }

        $position = $this->hasSubject() && null !== $this->getSubject()->getPosition() ? $this->getSubject()->getPosition() : 0;

        $formMapper
            ->end()
            ->with('group_options', ['class' => 'col-md-6'])
                ->add('enabled', CheckboxType::class, [
                    'required' => false,
                ])
                ->add('position', IntegerType::class, [
                    'required' => false,
                    'data' => $position,
                ])
            ->end()
        ;

        /*
        if (interface_exists(MediaInterface::class)) {
            $formMapper
                ->with('group_general')
                    ->add('media', ModelListType::class, [
                        'required' => false,
                    ], [
                        'link_parameters' => [
                            'provider' => 'sonata.media.provider.image',
                            'context' => 'sonata_category',
                        ],
                    ])
                ->end();
        }*/
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        parent::configureDatagridFilters($datagridMapper);

        $datagridMapper
            ->add('name')
            ->add('enabled')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('context', null, [
                'sortable' => 'context.name',
            ])
            ->add('slug')
            ->add('description')
            ->add('enabled', null, ['editable' => true])
            ->add('position')
            ->add('parent', null, [
                'sortable' => 'parent.name',
            ])
        ;
    }

    protected function configureSideMenu(MenuItemInterface $menu, $action, AdminInterface $childAdmin = null)
    {
        parent::configureSideMenu($menu, $action, $childAdmin); // TODO: Change the autogenerated stub
    }

    public function __construct($code, $class, $baseControllerName, ContextManagerInterface $contextManager)
    {
        parent::__construct($code, $class, CategoryAdminController::class, $contextManager);
    }
}
