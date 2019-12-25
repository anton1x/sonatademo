<?php

namespace App\Model;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Sonata\ClassificationBundle\Model\CategoryManagerInterface;
use App\Model\ProductsCategoryModel;

class CategoryModelFactory
{
    private $manager;

    public function __construct(CategoryManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function createModel($modelType): CategoryModelInterface
    {
        if($modelType == 'products'){
            $model = new ProductsCategoryModel();
            $this->initModel($model, $modelType);
            return $model;
        }
        else{
            throw new \InvalidArgumentException("Wrong model type {$modelType}");
        }
    }

    private function initModel(CategoryModelInterface $categoryModel, $context)
    {
        $categoryModel->setContext($context);
        $categoryModel->setCategoryManager($this->manager);
    }

}