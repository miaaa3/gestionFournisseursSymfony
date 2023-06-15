<?php

namespace App\Controller;

use App\Entity\Supplier;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SupplierController extends AbstractController
{
    #[Route('/insert_supplier', name: 'app_supplier')]
    public function insertSupplier(ManagerRegistry $doctrine, Request $request): Response
    {
        $post_data = json_decode($request->getContent(), true);
        
        $name = $post_data['name'];
        $email = $post_data['email'];
        $address = $post_data['address'];
        $phone = $post_data['phone'];
        $picture = $post_data['picture'];
        $city = $post_data['city'];

        $entityManager = $doctrine->getManager();

        $supplier = new Supplier();
        $supplier->setName($name);
        $supplier->setEmail($email);
        $supplier->setAddress($address);
        $supplier->setPhone($phone);
        $supplier->setPicture($picture);
        $supplier->setCity($city);

        $entityManager->persist($supplier);
        $entityManager->flush();

        return $this->json(['code' => '200', 'message'=> 'Supplier inserted successfully']);
    }

    #[Route('/getOneSupplier', name: 'getOneSupplier')]
    public function getOneSupplier(ManagerRegistry $doctrine, Request $request): Response
    {
        $id = $request->get('id');

        $supplier = $doctrine->getRepository(Supplier::class)->find($id);

        return $this->json($supplier);
    }

    #[Route('/delete_supplier', name: 'delete_supplier')]
    public function deleteSupplier(ManagerRegistry $doctrine, Request $request): Response
    {
        $id = $request->get('id');

        $entityManager = $doctrine->getManager();
        $supplier = $entityManager->getRepository(Supplier::class)->find($id);

        if (!$supplier) {
            throw $this->createNotFoundException('No supplier with this id: '.$id);
        }

        $entityManager->remove($supplier);
        $entityManager->flush();

        return $this->json(['code' => '200', 'message' => 'Supplier deleted successfully']);
    }

    #[Route('/list_suppliers', name: 'list_suppliers')]
    public function getSuppliers(ManagerRegistry $doctrine, Request $request): Response
    {
        $allSuppliers = $doctrine->getRepository(Supplier::class)->findAll();

        $suppliers = [];
        foreach ($allSuppliers as $supplier) {
            $suppliers[] = [
                'id' => $supplier->getId(),
                'name' => $supplier->getName(),
                'email' => $supplier->getEmail(),
                'address' => $supplier->getAddress(),
                'phone' => $supplier->getPhone(),
                'picture' => $supplier->getPicture(),
                'city' => $supplier->getCity(),
                'createdAt' => $supplier->getCreatedAt(),
            ];
        }

        return $this->json($suppliers);
    }

    #[Route('/update_supplier', name: 'update_supplier')]
    public function updateSupplier(ManagerRegistry $doctrine, Request $request): Response
    {
        $post_data = json_decode($request->getContent(), true);
        
        $id = isset($post_data['id']) ? $post_data['id'] : '';
        $name = isset($post_data['name']) ? $post_data['name'] : '';
        $email = isset($post_data['email']) ? $post_data['email'] : '';
        $address = isset($post_data['address']) ? $post_data['address'] : '';
        $phone = isset($post_data['phone']) ? $post_data['phone'] : '';
        $picture = isset($post_data['picture']) ? $post_data['picture'] : '';
        $city = isset($post_data['city']) ? $post_data['city'] : '';

        $entityManager = $doctrine->getManager();
        $supplier = $entityManager->getRepository(Supplier::class)->find($id);

        if (!$supplier) {
            throw $this->createNotFoundException('No supplier with this id: '.$id);
        }

        $supplier->setName($name);
        $supplier->setEmail($email);
        $supplier->setAddress($address);
        $supplier->setPhone($phone);
        $supplier->setPicture($picture);
        $supplier->setCity($city);

        $entityManager->flush();

        return $this->json(['code' => '200', 'message' => 'Supplier updated successfully']);
    }
}
