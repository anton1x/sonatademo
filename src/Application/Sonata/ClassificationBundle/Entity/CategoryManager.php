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

namespace App\Application\Sonata\ClassificationBundle\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Sonata\ClassificationBundle\Model\CategoryInterface;
use Sonata\ClassificationBundle\Model\ContextInterface;
use Sonata\ClassificationBundle\Model\ContextManagerInterface;
use Sonata\DatagridBundle\Pager\Doctrine\Pager;
use Sonata\DatagridBundle\ProxyQuery\Doctrine\ProxyQuery;
use Sonata\ClassificationBundle\Entity\CategoryManager as BaseCategoryManager;

class CategoryManager extends BaseCategoryManager
{

    /**
     * Load all categories from the database, the current method is very efficient for < 256 categories.
     */
    protected function loadCategories(ContextInterface $context)
    {
        if (\array_key_exists($context->getId(), $this->categories)) {
            return;
        }

        $class = $this->getClass();

        $categories = $this->getObjectManager()->createQuery(sprintf('SELECT c FROM %s c WHERE c.context = :context ORDER BY c.parent ASC', $class))
            ->setParameter('context', $context->getId())
            ->execute();

        if (0 === \count($categories)) {
            // no category, create one for the provided context
            $category = $this->create();
            $category->setName($context->getName());
            $category->setCode(sprintf('context_%s', $context->getName()));
            $category->setEnabled(true);
            $category->setContext($context);
            $category->setDescription($context->getName());

            $this->save($category);

            $categories = [$category];
        }

        $rootCategories = [];
        foreach ($categories as $pos => $category) {
            if (null === $category->getParent()) {
                $rootCategories[] = $category;
            }

            $this->categories[$context->getId()][$category->getId()] = $category;

            $parent = $category->getParent();

            $category->disableChildrenLazyLoading();

            if ($parent) {
                $parent->addChild($category);
            }
        }

        $this->categories[$context->getId()] = $rootCategories;
    }

    public function loadCategoriesForAdminForm($context)
    {
        /**
         * @var $repo CategoryRepository
         */
        $repo = $this->getRepository();

        return $repo->getQueryBuilderForFilterCategoryByContext($context)
            ->addOrderBy('parent.id')
            ->getQuery()->execute();
    }

    public function loadChildrenCategoriesByParentCode($code, $context)
    {
        $repo = $this->getRepository();

        $result = [];

        $items = $repo->findBy([
            'code' => $code,
            'context' => $context,
        ]);

        if (count($items) > 0) {
            foreach ($items as $item) {
                $children = $item->getChildren()->toArray();
                if(count($children) > 0)
                    $result =  array_merge($result, $children);
            }
        }


        return $result;
    }

}
