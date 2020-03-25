<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Document;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\Form\Validator\ErrorElement;
use Sonata\MediaBundle\Form\Type\MediaType;
use Sonata\MediaBundle\Validator\Constraints\ValidMediaFormat;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

final class DocumentAdmin extends AbstractAdmin
{

    protected $datagridValues = [
        '_page' => 1,
        '_sort_order' => 'asc',
        '_sort_by' => 'position'
    ];

    protected function getAllowedMimeTypes()
    {
        return [
            'application/pdf',
            'application/x-pdf',
            'application/x-rar-compressed',
            'application/octet-stream',
            'application/x-rar'

        ];
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('id')
            ->add('name')
            ->add('documentGroup')
            ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('id')
            ->add('name')
            ->add('position', null, [
                'label' => 'Сортировка',
                'editable' => true,
            ])
            ->add('parent.name', null, [
                'label' => 'Прикреплен к',
            ])
            ->add('documentGroup.title', null, [
                'label' => 'Группа',
            ])
            ->add('_action', null, [
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    private function getDocumentRepository()
    {
        return $this->getConfigurationPool()->getContainer()->get('doctrine.orm.entity_manager')->getRepository($this->getClass());
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {

        $formMapper
            ->with('Основное', ['class' => 'col-md-6'])
                ->add('name')
                ->add('documentGroup', ModelListType::class, [
                    'label' => 'Группа',
                    'btn_delete' => false,
                    'btn_edit' => false,
                    'btn_add' => 'Создать новую',
                ])
                ->add('parent', EntityType::class, [
                    'label' => 'Родительский файл',
                    'required' => false,
                    'class' => $this->getClass(),
                    'choices' => $this->getDocumentRepository()->getRootItems($this->getSubject()),
                ])
            ->end()
            ->with('Файл', ['class'=>'col-md-6'])
                ->add('file', MediaType::class, [
                    'context' => 'documents',
                    'provider' => 'sonata.media.provider.file',
                    'label' => false,
                ])
            ->end()
            ;
    }

    public function validate(ErrorElement $errorElement, $object)
    {

        $file = $object->getFile();

        if (null == $file) {
            $errorElement
                ->with('file')
                ->addViolation('Файл не выбран')
                ->end()
            ;
        }

        $type = $file->getContentType();

        if (!in_array($type, $this->getAllowedMimeTypes())) {
            $errorElement
                ->with('file')
                    ->addViolation('Неправильный формат файла')
                ->end()
            ;
        }

    }



}
