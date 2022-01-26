<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ProductController extends AbstractController
{
    /**
     * @Route("api/v1/products", name="api_list_products", methods={"Get"})
     */
    public function listProducts(ProductRepository $productRepository, SerializerInterface $serializer): Response
    {
        $products = $productRepository->getAllProducts();
        $json = $serializer->serialize($products, 'json');
        $response = new Response($json, 200, [], true);
        
        return $response;
    }

    /**
     * @Route("api/v1/products/{id}", name="api_show_product", methods={"Get"})
     */
    public function showProduct($id, ProductRepository $productRepository, SerializerInterface $serializer): Response
    {
        $products = $productRepository->getOneProduct($id);
        $json = $serializer->serialize($products, 'json');
        $response = new Response($json, 200, [], true);
        
        return $response;
    }
}
