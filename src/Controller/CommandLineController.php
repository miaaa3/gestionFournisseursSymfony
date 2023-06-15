<?php

namespace App\Controller;

use App\Entity\Command;
use App\Entity\CommandLine;
use App\Entity\Product;
use App\Repository\CommandLineRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandLineController extends AbstractController
{
    #[Route('/insert_command_line', name: 'app_command_line')]
public function insertCommandLine(ManagerRegistry $doctrine, Request $request): Response
{
    $post_data = json_decode($request->getContent(), true);

    $commandID = $post_data['commandID'];
    $productID = $post_data['productID'];
    $quantity = $post_data['quantity'];

    $entityManager = $doctrine->getManager();

    // Fetch the command entity
    $command = $entityManager->getRepository(Command::class)->find($commandID);

    // Fetch the product entity
    $product = $entityManager->getRepository(Product::class)->find($productID);

    // Calculate the total price based on the product's unit price and quantity
    $totalPrice = $product->getUnitPrice() * $quantity;

    // Create a new command line entity
    $commandLine = new CommandLine();
    $commandLine->setCommandID($commandID);
    $commandLine->setProductID($productID);
    $commandLine->setQuantity($quantity);
    $commandLine->setTotalPrice($totalPrice);
    $commandLine->setCommand($command);
    $commandLine->setProduct($product);

    $entityManager->persist($commandLine);
    $entityManager->flush();

    return $this->json(['code' => '200', 'message' => 'Command line inserted successfully']);
}


    #[Route('/getOneCommandLine', name: 'getOneCommandLine')]
    public function getOneCommandLine(ManagerRegistry $doctrine, Request $request): Response
    {
        $id = $request->get('id');

        $commandLine = $doctrine->getRepository(CommandLine::class)->find($id);

        return $this->json($commandLine);
    }

    #[Route('/delete_command_line', name: 'delete_command_line')]
    public function deleteCommandLine(ManagerRegistry $doctrine, Request $request): Response
    {
        $id = $request->get('id');

        $entityManager = $doctrine->getManager();
        $commandLine = $entityManager->getRepository(CommandLine::class)->find($id);

        if (!$commandLine) {
            throw $this->createNotFoundException('No command line with this id: '.$id);
        }

        $entityManager->remove($commandLine);
        $entityManager->flush();

        return $this->json(['code' => '200', 'message' => 'Command line deleted successfully']);
    }

    #[Route('/list_command_lines', name: 'list_command_lines')]
    public function getCommandLines(ManagerRegistry $doctrine, Request $request): Response
    {
        $allCommandLines = $doctrine->getRepository(CommandLine::class)->findAll();

        $commandLines = [];
        foreach ($allCommandLines as $commandLine) {
            $commandLines[] = [
                'id' => $commandLine->getId(),
                'commandID' => $commandLine->getCommandID(),
                'productID' => $commandLine->getProductID(),
                'quantity' => $commandLine->getQuantity(),
                'total_price' => $commandLine->getTotalPrice(),
            ];
        }

        return $this->json($commandLines);
    }

    #[Route('/update_command_line', name: 'update_command_line')]
    public function updateCommandLine(ManagerRegistry $doctrine, Request $request): Response
    {
        $post_data = json_decode($request->getContent(), true);

        $id = isset($post_data['id']) ? $post_data['id'] : '';
        $commandID = isset($post_data['commandID']) ? $post_data['commandID'] : '';
        $productID = isset($post_data['productID']) ? $post_data['productID'] : '';
        $quantity = isset($post_data['quantity']) ? $post_data['quantity'] : '';
        $totalPrice = isset($post_data['total_price']) ? $post_data['total_price'] : '';

        $entityManager = $doctrine->getManager();
        $commandLine = $entityManager->getRepository(CommandLine::class)->find($id);

        if (!$commandLine) {
            throw $this->createNotFoundException('No command line with this id: '.$id);
        }

        $commandLine->setCommandID($commandID);
        $commandLine->setProductID($productID);
        $commandLine->setQuantity($quantity);
        $commandLine->setTotalPrice($totalPrice);

        $entityManager->flush();

        return $this->json(['code' => '200', 'message' => 'Command line updated successfully']);
    }
}
