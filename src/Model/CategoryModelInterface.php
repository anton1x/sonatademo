<?php

namespace App\Model;

use Sonata\ClassificationBundle\Model\CategoryManagerInterface;

interface CategoryModelInterface
{
    public function getContext();

    public function setContext($context);

    public function setCategoryManager(CategoryManagerInterface $categoryManager);

    public function getCategoryManager():CategoryManagerInterface;
}