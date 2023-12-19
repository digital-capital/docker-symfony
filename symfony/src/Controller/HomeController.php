<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Message\MessageNotification;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/page-2', name: 'app_page_2')]
    public function nextPage(): Response
    {
        return new Response('Next Page 2');

        // return $this->render('home/index.html.twig', [
        //     'controller_name' => 'HomeController',
        // ]);
    }

    #[Route('/page-create-product', name: 'app_page_create_product')]
    public function createProduct(ManagerRegistry $managerRegistry): Response
    {
        $product1 = new Product();

        $product1->setName('Product 1');
        $product1->setPrice(1000);
        $product1->setDescription('First product');

        $product2 = new Product();

        $product2->setName('Product 2');
        $product2->setPrice(1000);
        $product2->setDescription('Product 2');

        $managerRegistry->getManager()->persist($product1);
        $managerRegistry->getManager()->persist($product2);

        $managerRegistry->getManager()->flush();

       return new Response('Creation produit');
    }


    #[Route('/page-create-category', name: 'app_page_create_category')]
    public function createCategory(ManagerRegistry $managerRegistry): Response
    {
        $category = new Category();
        $category->setName('Category 1');

        $managerRegistry->getManager()->persist($category);

        $managerRegistry->getManager()->flush();
        
        
        return new Response('Next Page 2');
    }

    #[Route('/page-relation-category-product', name: 'app_page_relation_category_product')]
    public function productToCategory(ManagerRegistry $managerRegistry): Response
    {
        $category =$managerRegistry->getManager()
        ->getRepository(Category::class)
        ->find(1);

        //dd($category);
        
        $product1= $managerRegistry->getManager()
        ->getRepository(Product::class)
        ->find(1);

        // dd($product1);

        $product1->setCategory($category);

        //$category->addProduct($product1);

        $managerRegistry->getManager()->persist($product1);
        $managerRegistry->getManager()->persist($category);

        $managerRegistry->getManager()->flush();
        
        return new Response('Next Page 2');
    }

    #[Route('/amqp', name: 'app_amqp')]
    public function amqp(MessageBusInterface $bus): Response
    {
        for($i=0;$i<100;$i++){
            $bus->dispatch(new MessageNotification('Mon Message # '.$i));
        }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

}
