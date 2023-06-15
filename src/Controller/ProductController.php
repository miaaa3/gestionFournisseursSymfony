<?php

namespace App\Controller;

use App\Entity\Supplier;
use App\Entity\Product;
use App\Repository\SupplierRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/insert_product', name: 'app_product')]
    public function insertProduct(ManagerRegistry $doctrine, Request $request): Response
    {
        $post_data = json_decode($request->getContent(), true);

        $name = $post_data['name'];
        $description = $post_data['description'];
        $unitPrice = $post_data['unit_price'];
        $supplierID = $post_data['supplierID'];

        $entityManager = $doctrine->getManager();

        $product = new Product();
        $product->setName($name);
        $product->setDescription($description);
        $product->setUnitPrice($unitPrice);
        $product->setSupplierID($supplierID);

        
        //$supplierId is the ID of an existing Supplier entity
        $supplier = $entityManager->getRepository(Supplier::class)->find($supplierID);
        $product->setSupplier($supplier);


        $entityManager->persist($product);
        $entityManager->flush();

        return $this->json(['code' => '200', 'message'=> 'Product inserted successfully']);
    }

    #[Route('/getOneProduct', name: 'getOneProduct')]
    public function getOneProduct(ManagerRegistry $doctrine, Request $request): Response
    {
        $id = $request->get('id');

        $product = $doctrine->getRepository(Product::class)->find($id);

        return $this->json($product);
    }

    #[Route('/delete_product', name: 'delete_product')]
    public function deleteProduct(ManagerRegistry $doctrine, Request $request): Response
    {
        $id = $request->get('id');

        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException('No product with this id: '.$id);
        }

        $entityManager->remove($product);
        $entityManager->flush();

        return $this->json(['code' => '200', 'message' => 'Product deleted successfully']);
    }

    #[Route('/list_products', name: 'list_products')]
    public function getProducts(ManagerRegistry $doctrine, Request $request): Response
    {
        $allProducts = $doctrine->getRepository(Product::class)->findAll();

        $products = [];
        foreach ($allProducts as $product) {
            $products[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'description' => $product->getDescription(),
                'unit_price' => $product->getUnitPrice(),
                'supplierID' => $product->getSupplierID(),
            ];
        }

        return $this->json($products);
    }

    #[Route('/update_product', name: 'update_product')]
    public function updateProduct(ManagerRegistry $doctrine, Request $request): Response
    {
        $post_data = json_decode($request->getContent(), true);

        $id = isset($post_data['id']) ? $post_data['id'] : '';
        $name = isset($post_data['name']) ? $post_data['name'] : '';
        $description = isset($post_data['description']) ? $post_data['description'] : '';
        $unitPrice = isset($post_data['unit_price']) ? $post_data['unit_price'] : '';
        $supplierID = isset($post_data['supplierID']) ? $post_data['supplierID'] : '';

        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException('No product with this id: '.$id);
        }

        $product->setName($name);
        $product->setDescription($description);
        $product->setUnitPrice($unitPrice);
        $product->setSupplierID($supplierID);

        $entityManager->flush();

        return $this->json(['code' => '200', 'message' => 'Product updated successfully']);
    }
}
