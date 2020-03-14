<?php


namespace App\Form\Extension;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ChoiceTypeExtension
 * @package App\Application\Sonata\ClassificationBundle\Form\Extension
 *
 * Add "select/deselect all" buttons for the multiple choice type
 */
class SelectAllExtension extends AbstractTypeExtension
{
    public static function getExtendedTypes()
    {
        return [EntityType::class];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefined(['show_select_all', 'show_unselect_all'])
            ->setAllowedTypes('show_select_all', 'boolean')
            ->setDefaults([
                'show_select_all' => false,
                'show_unselect_all' => false
            ])
            ->setAllowedTypes('show_unselect_all', 'boolean')
        ;
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['show_select_all'] = false;
        $view->vars['show_unselect_all'] = false;

        if($options['multiple'] && $options['expanded']){
            $view->vars['show_select_all'] = $options['show_select_all'];
            $view->vars['show_unselect_all'] = $options['show_unselect_all'];
        }

    }


}