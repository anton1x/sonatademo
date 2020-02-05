<?php


namespace App\Application\Sonata\ClassificationBundle\Form\Type;

use Sonata\ClassificationBundle\Form\Type\CategorySelectorType as BaseCategorySelectorType;
use Sonata\ClassificationBundle\Model\CategoryInterface;
use Symfony\Component\OptionsResolver\Options;

class CategorySelectorType extends BaseCategorySelectorType
{

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
            $categories = $this->manager->getRootCategoriesForContext($options['context']);
        }

        $choices = [];

        foreach ($categories as $category) {
            $choices[$category->getId()] = sprintf('%s (%s)', $category->getName(), $category->getContext()->getId());

            $this->childWalker($category, $options, $choices);
        }

        return $choices;
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

            $choices[$child->getId()] = sprintf('%s %s', str_repeat('-', 2 * $level), $child);

            $this->childWalker($child, $options, $choices, $level + 1);
        }
    }
}