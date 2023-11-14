<?php

namespace App\Controller;

use App\Entity\Category;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class SimpleApiController extends AbstractController
{
    #[Route('/simple/api', name: 'app_simple_api')]
    public function index(ManagerRegistry $managerRegistry): JsonResponse
    {
        $categories= $managerRegistry->getManager()->getRepository(Category::class)->findAll();
        $data=[];
        foreach($categories as $category){
            if($category instanceof Category){
                $data['name']=$category->getName();
                $data['id']=$category->getId();
            }
        }

        return new JsonResponse($data);
    }
}
