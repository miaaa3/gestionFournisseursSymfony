<?php

namespace App\Controller;
use App\Entity\Supplier;
use App\Entity\Command;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandController extends AbstractController
{
    #[Route('/insert_command', name: 'app_command')]
    public function insertCommand(ManagerRegistry $doctrine, Request $request): Response
    {
        $post_data = json_decode($request->getContent(), true);
        
        $createdAt = new \DateTimeImmutable();
        $status = $post_data['status'];
        $paymentMethod = $post_data['paymentMethod'];
        $montantHT = $post_data['montantHT'];
        $deliveryMethod = $post_data['deliveryMethod'];
        $deliveryDelay = $post_data['deliveryDelay'];
        $shippingAddress = $post_data['shippingAddress'];
        $supplierId = $post_data['supplierID'];

        $entityManager = $doctrine->getManager();

        $command = new Command();
        $command->setCreatedAt($createdAt);
        $command->setStatus($status);
        $command->setPaymentMethod($paymentMethod);
        $command->setMontantHT($montantHT);
        $command->setDeliveryMethod($deliveryMethod);
        $command->setDeliveryDelay($deliveryDelay);
        $command->setShippingAddress($shippingAddress);
        $command->setSupplierID($supplierId);


        //$supplierId is the ID of an existing Supplier entity
        $supplier = $entityManager->getRepository(Supplier::class)->find($supplierId);
        $command->setSupplier($supplier);

        $entityManager->persist($command);
        $entityManager->flush();

        return $this->json(['code' => '200', 'message'=> 'Command inserted successfully']);
    }

    #[Route('/getOneCommand', name: 'getOneCommand')]
    public function getOneCommand(ManagerRegistry $doctrine, Request $request): Response
    {
        $id = $request->get('id');

        $command = $doctrine->getRepository(Command::class)->find($id);

        return $this->json($command);
    }

    #[Route('/delete_command', name: 'delete_command')]
    public function deleteCommand(ManagerRegistry $doctrine, Request $request): Response
    {
        $id = $request->get('id');

        $entityManager = $doctrine->getManager();
        $command = $entityManager->getRepository(Command::class)->find($id);

        if (!$command) {
            throw $this->createNotFoundException('No command with this id: '.$id);
        }

        $entityManager->remove($command);
        $entityManager->flush();

        return $this->json(['code' => '200', 'message' => 'Command deleted successfully']);
    }

    #[Route('/list_commands', name: 'list_commands')]
    public function getCommands(ManagerRegistry $doctrine, Request $request): Response
    {
        $allCommands = $doctrine->getRepository(Command::class)->findAll();

        $commands = [];
        foreach ($allCommands as $command) {
            $commands[] = [
                'id' => $command->getId(),
                'createdAt' => $command->getCreatedAt(),
                'status' => $command->getStatus(),
                'paymentMethod' => $command->getPaymentMethod(),
                'montantHT' => $command->getMontantHT(),
                'deliveryMethod' => $command->getDeliveryMethod(),
                'deliveryDelay' => $command->getDeliveryDelay(),
                'shippingAddress' => $command->getShippingAddress(),
                'supplier' => $command->getSupplier(),
            ];
        }

        return $this->json($commands);
    }

    #[Route('/update_command', name: 'update_command')]
    public function updateCommand(ManagerRegistry $doctrine, Request $request): Response
    {
        $post_data = json_decode($request->getContent(), true);
        
        $id = isset($post_data['id']) ? $post_data['id'] : '';
        $status = isset($post_data['status']) ? $post_data['status'] : '';
        $paymentMethod = isset($post_data['paymentMethod']) ? $post_data['paymentMethod'] : '';
        $montantHT = isset($post_data['montantHT']) ? $post_data['montantHT'] : '';
        $deliveryMethod = isset($post_data['deliveryMethod']) ? $post_data['deliveryMethod'] : '';
        $deliveryDelay = isset($post_data['deliveryDelay']) ? $post_data['deliveryDelay'] : '';
        $shippingAddress = isset($post_data['shippingAddress']) ? $post_data['shippingAddress'] : '';
        $supplierId = isset($post_data['supplierID']) ? $post_data['supplierID'] : '';


        $entityManager = $doctrine->getManager();
        $command = $entityManager->getRepository(Command::class)->find($id);

        if (!$command) {
            throw $this->createNotFoundException('No command with this id: '.$id);
        }

        $command->setStatus($status);
        $command->setPaymentMethod($paymentMethod);
        $command->setMontantHT($montantHT);
        $command->setDeliveryMethod($deliveryMethod);
        $command->setDeliveryDelay($deliveryDelay);
        $command->setShippingAddress($shippingAddress);
        $command->setSupplierID($supplierId);
        
        $entityManager->flush();

        return $this->json(['code' => '200', 'message' => 'Command updated successfully']);
    }
}
