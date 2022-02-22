<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use JMS\Serializer\SerializerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use OpenApi\Annotations as OA;
use Knp\Component\Pager\PaginatorInterface;

class ProductController extends AbstractController
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function  __construct(SerializerInterface $serializer) {
        $this->serializer = $serializer;
    }
    /**
     * Return a list of phones ressource
     * 
     * @Rest\Get(path="/api/mobiles", name="api_list_mobiles")
     * @Rest\View(statusCode= 200)
     * @OA\Get(
     *     path="/mobiles",
     *     tags={"Mobiles"},
     *     security={"bearer"},
     *     @OA\Parameter(
     *          name="page",
     *          in="query",
     *          description="Page number",
     *          required=false,
     *          @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="List of the mobile phones",
     *          @OA\JsonContent(type="array",@OA\Items(ref="#/components/schemas/Product")),
     *     ),
     *     @OA\Response(response=401, description="JWT Token not found or expired"),
     *     @OA\Response(response=404, description="Page not found") 
     * )
     * @param ProductRepository $productRepository
     * @param TagAwareCacheInterface $cache
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return response
     */
    public function listMobiles(ProductRepository $productRepository, TagAwareCacheInterface $cache, Request $request, PaginatorInterface $paginator): Response
    {
        //recover the page
        $page = $request->query->getInt("page", 1);
       
        //search all mobiles using the cache
        $mobilesCache = $cache->get("products".$page, function(ItemInterface $item) use($page, $paginator, $productRepository){
            $item->expiresAfter(3600);
            $item->tag('mobile');
            
            //recover all mobiles
            $datas = $productRepository->findAll();
            //recover a page with 6 mobiles
            $products = $paginator->paginate($datas, $page, 6);
            
            $json = $this->serializer->serialize($products, 'json');
            $response = new Response($json, 200, [], true);
            
            return $response;
        });

        return $mobilesCache;
    }

    /**
     * Return phone details
     * 
     * @Rest\Get(path="/api/mobiles/{id}", name="api_details_mobile")
     * @Rest\View(statusCode= 200)
     * @OA\Get(
     *     path="/mobiles/{id}",
     *     tags={"Mobiles"},
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
     * @param CacheInterface $cache
     * @return response
     */
    public function showMobile($id, ProductRepository $productRepository, CacheInterface $cache): Response
    {
        //search one mobile using the cache
        $mobileCache = $cache->get("product_details".$id, function(ItemInterface $item) use($id, $productRepository){
            $item->expiresAfter(3600);
            
            //recover one mobile
            $product = $productRepository->findById($id);
            $json = $this->serializer->serialize($product, 'json');

            $response = new Response($json, 200, [], true);
            
            return $response;
        });

        return $mobileCache;
    }
}
