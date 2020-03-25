<?php


namespace App\Application\Sonata\ClassificationBundle\Form\Type;

use App\Application\Sonata\ClassificationBundle\Entity\Category;
use App\Repository\CategoryRepository;
use Sonata\ClassificationBundle\Form\ChoiceList\CategoryChoiceLoader;
use Sonata\ClassificationBundle\Form\Type\CategorySelectorType as BaseCategorySelectorType;
use Sonata\ClassificationBundle\Model\CategoryInterface;
use Sonata\Doctrine\Model\ManagerInterface;
use Symfony\Component\Form\ChoiceList\Loader\ChoiceLoaderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategorySelectorType extends BaseCategorySelectorType
{

    public function configureOptions(OptionsResolver $resolver)
    {
        $that = $this;

        $resolver->setDefaults([
            'context' => null,
            'category' => null,
            'choice_loader' => static function (Options $opts, $previousValue) use ($that) {
                return new CategoryChoiceLoader(array_flip($that->getChoices($opts)));
            },
            'sonata_field_description' => [
                'group_by' => 'parent',
            ],
            'choice_attr' => static function ($key, $value) use ($that) {
                /**
                 * @var $category Category
                 */
                $category = $that->manager->find($key);
                if ($category->hasChildren())
                    return [
                        'disabled' => true,
                        'class'=> 'font-weight-bold'
                    ];
                else
                    return [];
            }
        ]);
    }


    /**
     * @return array
     */
    public function getChoices(Options $options)
    {
        if (!$options['category'] instanceof CategoryInterface) {
            return [];
        }

        if (null === $options['context']) {
            $categories = $this->manager->getAllRootCategories();
        } else {
            //$categories = $this->manager->getRootCategoriesForContext($options['context']);
            $categories = $this->manager->loadCategoriesForAdminForm($options['context']);
        }

        $choices = [];

        foreach ($categories as $category) {
            $choices[$category->getId()] = sprintf('%s (%s)', $category->getName(), $category->getParent()->getName());

            $this->childWalker($category, $options, $choices);
        }

        return $choices;
    }


    public function __construct(ManagerInterface $manager)
    {
        parent::__construct($manager);
    }

    /**
     * @param int $level
     */
    private function childWalker(CategoryInterface $category, Options $options, array &$choices, $level = 2)
    {
        if (null === $category->getChildren()) {
            return;
        }

        foreach ($category->getChildren() as $child) {
            if ($options['category'] && $options['category']->getId() === $child->getId()) {
                continue;
            }

            $choices[$child->getId()] = sprintf('%s %s', str_repeat('-', 3 * $level), $child);

            $this->childWalker($child, $options, $choices, $level + 1);
        }
    }
}