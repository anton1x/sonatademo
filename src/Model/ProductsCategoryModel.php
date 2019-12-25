<?php

namespace App\Model;

use App\Application\Sonata\ClassificationBundle\Entity\Category;
use Sonata\ClassificationBundle\Entity\CategoryManager;
use Sonata\ClassificationBundle\Model\CategoryManagerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class ProductsCategoryModel implements CategoryModelInterface
{
    private $categoryManager;

    private $context;


    public function getContext()
    {
        return $this->context;
    }

    public function setContext($context)
    {
        $this->context = $context;
    }

    /**
     * @return CategoryManagerInterface
     */
    public function getCategoryManager(): CategoryManagerInterface
    {
        return $this->categoryManager;
    }

    /**
     * @param CategoryManagerInterface $categoryManager
     */
    public function setCategoryManager(CategoryManagerInterface $categoryManager): void
    {
        $this->categoryManager = $categoryManager;
    }

    public function getCategories()
    {
        $cats = $this->categoryManager->getCategories($this->getContext());
        foreach ($cats as $cat){
            dump($cat->getSlugPath());
        }
        return $cats;
    }

}