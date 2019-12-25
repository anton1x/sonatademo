<?php


namespace App\Controller;


use App\Application\Sonata\ClassificationBundle\Entity\Category;
use App\Basket\BasketAwareInterface;
use App\Model\ProductsCategoryModel;
use App\Repository\ProductsRepository;
use Sonata\ClassificationBundle\Entity\CategoryManager;
use Sonata\ClassificationBundle\Model\CategoryManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController implements BasketAwareInterface
{
    
    /**
     * @Route(name="products_index", path="/")
     */
//    public function indexAction()
//    {
//        return new Response('df');
//    }

    /**
     * @Route(path="products/{path<[0-9A-Za-z\-\/]+>}", name="products_catalogue_list")
     */
    public function catalogListAction($path, ProductsRepository $productsRepository)
    {
        $items = $productsRepository->getProductsBySlugPath($path);
        dump($items);
        //throw new \Exception('324');
        return $this->render('products_list.twig', [
            'items' => $items,
        ]);
    }

}