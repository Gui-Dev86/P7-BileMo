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
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use OpenApi\Annotations as OA;
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
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param PaginatorInterface $paginator
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

        $json = $serializer->serialize($users, 'json', ['groups' => 'user']);

        $response = new Response($json, 200, [], true);
        
        return $response;

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
            $json = $serializer->serialize($user, 'json', ['groups' => 'user']);
            $response = new Response($json, 200, [], true);
            return $response;
        }
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
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param ValidatorInterface $validator
     * @param UserPasswordEncoderInterface $encoder
     * @return response
     */
    public function addUser(SerializerInterface $serializer, Request $request, EntityManagerInterface $manager, ValidatorInterface $validator, UserPasswordEncoderInterface $encoder): Response
    {
        $json = $request->getContent();
        //transform the datas in object
        $user = $serializer->deserialize($json, User::class, 'json', ['groups' => 'user']);
        $errors = $validator->validate($user);

        if(count($errors) > 0) {
            $data = $serializer->serialize($errors, 'json');
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
        
        $json = $serializer->serialize($user, 'json', ['groups' => 'user']);
        $response = new Response($json, 200, [], true);
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
        else 
        {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($user);
            $manager->flush();

            return new Response("The user has been deleted", 204);
            
        }
    }
}
