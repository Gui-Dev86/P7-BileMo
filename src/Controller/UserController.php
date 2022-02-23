<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Client;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Component\Serializer\SerializerInterface as SymfonySerializer;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use OpenApi\Annotations as OA;
use Knp\Component\Pager\PaginatorInterface;

class UserController extends AbstractController
{
    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var SymfonySerializer
     */
    private $deserializer;

    public function  __construct(SerializerInterface $serializer, SymfonySerializer $deserializer) {
        $this->serializer = $serializer;
        $this->deserializer = $deserializer;
    }
    /**
     * Return a list of users for the client
     * 
     * @Rest\Get(path="/api/users", name="api_list_users")
     * @Rest\View(statusCode= 200)
     * @OA\Get(
     *     path="/users",
     *     tags={"Users"},
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
     *          @OA\JsonContent(type="array",@OA\Items(ref="#/components/schemas/User")),
     *     ),
     *     @OA\Response(response=401, description="JWT Token not found or expired"),
     *     @OA\Response(response=404, description="Page not found")
     * )
     * @param UserRepository $userRepository
     * @param TagAwareCacheInterface $cache
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return response
     */
    public function listUsers(UserRepository $userRepository, TagAwareCacheInterface $cache, Request $request, PaginatorInterface $paginator): Response
    {
        //recover the client id connected
        $client = $this->getUser();
        $idClient = $client->getId();

        //recover the page
        $page = $request->query->getInt("page", 1);

        //search all userss using the cache
        $usersCache = $cache->get("users".$page, function(ItemInterface $item) use($page, $idClient, $paginator, $userRepository){
            $item->expiresAfter(3600);
            $item->tag('user');

            //recover the users of the client connected
            $datas = $userRepository->findByClient($idClient);
            //recover a page with 5 users
            $users = $paginator->paginate($datas, $page, 5);

            $json = $this->serializer->serialize($users, 'json');
            $response = new Response($json, 200, [], true);

            return $response;
        });

        return $usersCache;
    }

    /**
     * Return user client details
     * 
     * @Rest\Get(path="/api/users/{id}", name="api_details_user")
     * @Rest\View(statusCode= 200)
     * @OA\Get(
     *     path="/users/{id}",
     *     tags={"Users"},
     *     security={"bearer"},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Id of the user",
     *          required=true,
     *          @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *       response="200",
     *         description="Details for one user",
     *         @OA\JsonContent(ref="#/components/schemas/User"),
     *     ),
     *     @OA\Response(response=401, description="JWT Token not found or expired"),
     *     @OA\Response(response=404, description="Page not found")
     * )
     * @param $id
     * @param UserRepository $userRepository
     * @param CacheInterface $cache
     * @return response
     */
    public function showUser($id, UserRepository $userRepository, CacheInterface $cache): Response
    {
        //recover the id of the client connected
        $client = $this->getUser();
        $idClient = $client->getId();

        //search one user using the cache
        $userCache = $cache->get("user_details".$id, function(ItemInterface $item) use($id, $idClient, $userRepository){
            $item->expiresAfter(3600);
            //recover one mobile
            //recover the datas user
            $user = $userRepository->find($id);
            //recover the client id of the user
            $userClient = $user->getClient();
            $idUserClient = $userClient->getId();
            //verify if the client has access to this user
            if($idClient !== $idUserClient) {
                throw New HttpException(403, "You haven't access to this ressource.");
            }
            
            $json = $this->serializer->serialize($user, 'json');
            $response = new Response($json, 200, [], true);

            return $response;
        });

        return $userCache;
        }
        

    /**
     * Create a new user
     * 
     * @Rest\Post(path="/api/users", name="api_add_user")
     * @Rest\View(statusCode= 201)
     * @OA\Post(
     *     path="/users",
     *     tags={"Users"},
     *     security={"bearer"},
     *     @OA\Response(
     *          response="201",
     *          description="Creation of an user",
     *          @OA\JsonContent(
     *            ref="#/components/schemas/User"
     *          ),
     *     ),
     * @OA\RequestBody(
     *    required=true,
     *      @OA\MediaType(
     *        mediaType="application/json",
     *         @OA\Schema(
     *          @OA\Property(property="username", description="The username of the user.", type="string", example="usernameUser"),
     *          @OA\Property(property="password", description="Password of the user.", type="string", format="password", example="passwordUser123"),
     *          @OA\Property(property="firstname", description="Firstname of the new user.", type="string", example="John"),
     *          @OA\Property(property="lastname", description="Lastname of the new user.", type="string", example="Doe"),
     *          @OA\Property(property="email", description="Email address of the new user.", type="string", format="email", example="john_doe@gmail.fr")
     *        )
     *      )
     *    ),
     * @OA\Response(response=401, description="JWT Token not found or expired"),
     * @OA\Response(response=404, description="Page not found")
     * )
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param ValidatorInterface $validator
     * @param UserPasswordEncoderInterface $encoder
     * @return response
     */
    public function addUser(Request $request, EntityManagerInterface $manager, ValidatorInterface $validator, UserPasswordEncoderInterface $encoder): Response
    {
        $json = $request->getContent();
        //transform the datas in object
        $user = $this->deserializer->deserialize($json, User::class, 'json');
        $errors = $validator->validate($user);

        if(count($errors) > 0) {
            $data = $this->serializer->serialize($errors, 'json');
            $response =  new JsonResponse($data, 400, [], true);
            return $response;
        }

        $password = $encoder->encodePassword($user, $user->getPassword());
        $dateCreate = new \DateTime();

        $user->setPassword($password)
            ->setRoles(["ROLE_USER"])
            ->setDateCreate($dateCreate)
            ->setClient($this->getUser());
        $manager->persist($user);
        $manager->flush();
        
        $json = $this->serializer->serialize($user, 'json');
        $response = new Response($json, 201, [], true);
        return $response;
    }

    /**
     * Delete an user
     * 
     * @Rest\Delete(path="/api/users/{id}", name="api_delete_user")
     * @Rest\View(statusCode= 204)
     * @OA\Delete(
     *     path="/users/{id}",
     *     tags={"Users"},
     *     security={"bearer"},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Id of the user",
     *          required=true,
     *          @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *          response="204",
     *          description="The user has been deleted",
     *     ),
     *     @OA\Response(response=401, description="JWT Token not found or expired"),
     *     @OA\Response(response=404, description="Page not found")
     * )
     * @param $id
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $manager
     * @return response
     */
    public function deleteUser($id, UserRepository $userRepository): Response
    {
        //recover the id of the client connected
        $client = $this->getUser();
        $idClient = $client->getId();
        //recover the datas user
        $user = $userRepository->find($id);
        //recover the client id of the user
        $userClient = $user->getClient();
        $idUserClient = $userClient->getId();
        //verify if the client has access to this user
        if($idClient !== $idUserClient) {
            throw New HttpException(403, "You haven't access to this ressource.");
        }
        
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($user);
        $manager->flush();

        return new Response("The user has been deleted");
        
    }
}
