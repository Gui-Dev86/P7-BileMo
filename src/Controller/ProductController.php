<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use OpenApi\Annotations as OA;
use Knp\Component\Pager\PaginatorInterface;

class ProductController extends AbstractController
{
    /**
     * Return a list of phones ressource
     * 
     * @Rest\Get(path="/api/mobiles", name="api_list_mobiles")
     * @Rest\View(statusCode= 200)
     * @OA\Get(
     *     path="/mobiles",
     *     security={"bearer"},
     *     @OA\Response(
     *          response="200",
     *          description="List of the mobile phones",
     *          @OA\JsonContent(type="array",@OA\Items(ref="#/components/schemas/Product")),
     *     ),
     *     @OA\Response(response=401, description="JWT Token not found or expired"),
     *     @OA\Response(response=404, description="Page not found") 
     * )
     * @param ProductRepository $productRepository
     * @param SerializerInterface $serializer
     * @return response
     */
    public function listMobiles(ProductRepository $productRepository, SerializerInterface $serializer, Request $request, PaginatorInterface $paginator): Response
    {
        //recover all mobiles
        $datas = $productRepository->getAllProducts();
        //recover a page with 6 mobiles
        $products = $paginator->paginate($datas, $request->query->getInt('page', 1), 6);
        
        $json = $serializer->serialize($products, 'json');

        $response = new Response($json, 200, [], true);
        
        return $response;
    }

    /**
     * Return phone details
     * 
     * @Rest\Get(path="/api/mobiles/{id}", name="api_details_mobile")
     * @Rest\View(statusCode= 200)
     * @OA\Get(
     *     path="/mobiles/{id}",
     *     security={"bearer"},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Id of the mobile phone to get",
     *          required=true,
     *          @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Details for one mobile",
     *          @OA\JsonContent(ref="#/components/schemas/Product"),
     *     ),
     *     @OA\Response(response=401, description="JWT Token not found or expired"),
     *     @OA\Response(response=404, description="Page not found")
     * )
     * @param $id
     * @param ProductRepository $productRepository
     * @param SerializerInterface $serializer
     * @return response
     */
    public function showMobile($id, ProductRepository $productRepository, SerializerInterface $serializer): Response
    {
        $product = $productRepository->getOneProduct($id);
        $json = $serializer->serialize($product, 'json');

        $response = new Response($json, 200, [], true);
        
        return $response;
    }
}
