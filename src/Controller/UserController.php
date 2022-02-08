<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Client;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Knp\Component\Pager\PaginatorInterface;

class UserController extends AbstractController
{

    /**
     * Return a list of users for the client
     * 
     * @Rest\Get(path="/api/users", name="api_list_users")
     * @Rest\View(statusCode= 200)
     * @OA\Get(
     *     path="/users",
     *     security={"bearer"},
     *     @OA\Response(
     *          response="200",
     *          description="List of the mobile phones",
     *          @OA\JsonContent(type="array",@OA\Items(ref="#/components/schemas/User")),
     *     ),
     *     @OA\Response(response=401, description="JWT Token not found or expired"),
     *     @OA\Response(response=404, description="Page not found")
     * )
     * @param UserRepository $userRepository
     * @param SerializerInterface $serializer
     * @return response
     */
    public function listUsers(UserRepository $userRepository, SerializerInterface $serializer, Request $request, PaginatorInterface $paginator): Response
    {
        //recover the client id connected
        $client = $this->getUser();
        $idClient = $client->getId();
        //recover the users of the client connected
        $datas = $userRepository->findByClient($idClient);
        //recover a page with 5 users
        $users = $paginator->paginate($datas, $request->query->getInt('page', 1), 5);

        $json = $serializer->serialize($users, 'json', ['groups' => 'post:readUser']);

        $response = new Response($json, 200, [], true);
        
        return $response;

    }

    /**
     * Return user client details
     * 
     * @Rest\Get(path="/api/users/{id}", name="api_details_users")
     * @Rest\View(statusCode= 200)
     * @OA\Get(
     *     path="/users/{id}",
     *     security={"bearer"},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Id of the user",
     *          required=true,
     *          @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Details for one user",
     *          @OA\JsonContent(ref="#/components/schemas/User"),
     *     ),
     *     @OA\Response(response=401, description="JWT Token not found or expired"),
     *     @OA\Response(response=404, description="Page not found")
     * )
     * @param $id
     * @param UserRepository $userRepository
     * @param SerializerInterface $serializer
     * @return response
     */
    public function showUser($id, UserRepository $userRepository, SerializerInterface $serializer): Response
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
        else 
        {
            $json = $serializer->serialize($user, 'json', ['groups' => 'post:readUser']);
            $response = new Response($json, 200, [], true);
            return $response;
        }
    }
}
