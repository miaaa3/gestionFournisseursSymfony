<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/login', name: 'login', methods: ['POST'])]
    public function login(Request $request, Connection $connection): Response
    {
        $data = json_decode($request->getContent(), true);

        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        // Validate that email and password are provided
        if (!$email || !$password) {
            return $this->json(['error' => 'Invalid email or password'], Response::HTTP_BAD_REQUEST);
        }

        $query = $connection->createQueryBuilder()
        ->select('*')
        ->from('user')
        ->where('email = :email')
        ->setParameter('email', $email)
        ->setMaxResults(1);

        $result = $query->executeQuery();

        $user = $result->fetchAssociative();

        // If user does not exist, return an error response
        if (!$user) {
            return $this->json(['error' => 'Invalid email'], Response::HTTP_UNAUTHORIZED);
        }

        // If password is incorrect, return an error response
        if (!password_verify($password, $user['password'])) {
            return $this->json(['error' => 'Invalid password'], Response::HTTP_UNAUTHORIZED);
        }

        // You can customize the response data according to your needs
        $responseData = [
            'message' => 'Login successful',
            'user' => [
                'id' => $user['id'],
                'email' => $user['email'],
                // Add other user properties as needed
            ],
        ];

        return $this->json($responseData);
    }

    #[Route('/insert_user', name: 'app_user')]
    public function insertUser(ManagerRegistry $doctrine, Request $request): Response
    {
        $post_data = json_decode($request->getContent(), true);
        
        $name = $post_data['name'];
        $email = $post_data['email'];
        $password = $post_data['password'];
        $address = $post_data['address'];
        $phone = $post_data['phone'];
        $picture = $post_data['picture'];

        $entityManager = $doctrine->getManager();

        $user = new User();
        $user->setName($name);
        $user->setEmail($email);
        $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
        $user->setAddress($address);
        $user->setPhone($phone);
        $user->setPicture($picture);

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json(['code' => '200', 'message'=> "User registered successfully"]);
    }

    #[Route('/getOneUser', name: 'getOneUser')]
    public function getOneUser(ManagerRegistry $doctrine, Request $request): Response
    {
        $id = $request->get('id');

        $user = $doctrine->getRepository(User::class)->find($id);

        return $this->json($user);
    }

    #[Route('/delete_user', name: 'delete_user')]
    public function deleteUser(ManagerRegistry $doctrine, Request $request): Response
    {
        $id = $request->get('id');

        $entityManager = $doctrine->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('No user with this id: '.$id);
        }

        $entityManager->remove($user);
        $entityManager->flush();

        return $this->json(['code' => '200', 'message' => 'User deleted successfully']);
    }

    #[Route('/list_users', name: 'list_users')]
    public function getUsers(ManagerRegistry $doctrine, Request $request): Response
    {
        $allUsers = $doctrine->getRepository(User::class)->findAll();

        $users = [];
        foreach ($allUsers as $user) {
            $users[] = [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'password' => $user->getPassword(),
                'phone' => $user->getPhone(),
                'address' => $user->getAddress(),
                'picture' => $user->getPicture(),
            ];
        }

        return $this->json($users);
    }

    #[Route('/update_user', name: 'update_user')]
    public function updateUser(ManagerRegistry $doctrine, Request $request): Response
    {
        $post_data = json_decode($request->getContent(), true);
        
        $id = isset($post_data['id']) ? $post_data['id'] : '';
        $name = isset($post_data['name']) ? $post_data['name'] : '';
        $email = isset($post_data['email']) ? $post_data['email'] : '';
        $password = isset($post_data['password']) ? $post_data['password'] : '';
        $address = isset($post_data['address']) ? $post_data['address'] : '';
        $phone = isset($post_data['phone']) ? $post_data['phone'] : '';
        $picture = isset($post_data['picture']) ? $post_data['picture'] : '';

        $entityManager = $doctrine->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('No user with this id: '.$id);
        }

        $user->setName($name);
        $user->setEmail($email);
        $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
        $user->setAddress($address);
        $user->setPhone($phone);
        $user->setPicture($picture);

        $entityManager->flush();

        return $this->json(['code' => '200', 'message' => "User modified successfully"]);
    }
}
