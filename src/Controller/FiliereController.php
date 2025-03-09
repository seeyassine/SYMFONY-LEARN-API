<?php

namespace App\Controller;

use App\Entity\Filiere;
use App\Form\FiliereType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/filiere')]
final class FiliereController extends AbstractController{

    #[Route('/create', name: 'app_filiere')]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    { 
        $data = json_decode($request->getContent(), true);

        $F = new Filiere();

        $form = $this->createForm(FiliereType::class, $F);
        $form->submit($data);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($F);
            $em->flush($F);

            return new JsonResponse([
                'id' => $F->getId(),
                'status' => 'CREATED_SUCCESSFULLY',
            ], JsonResponse::HTTP_CREATED);
        }
       
        return new JsonResponse([
            'status' => 'FORM_ERROR',
            // 'errors' => $this->getFormErrors($form),
        ], JsonResponse::HTTP_BAD_REQUEST);
                
    }
}
