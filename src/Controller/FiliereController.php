<?php

namespace App\Controller;

use App\Entity\Filiere;
use App\Form\FiliereType;
use App\Repository\FiliereRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/filiere')]
final class FiliereController extends AbstractController{

    #[Route('/create', name: 'app_filiere', methods: 'POST')]
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

    #[Route('/edit/{id}', name: 'app_filiere_edit', methods: ['PUT'])]
    public function edit(
        int $id,
        EntityManagerInterface $em,
        FiliereRepository $filiereRepository, Request $request): JsonResponse
    {
        
        $filiere = $filiereRepository->find($id);
        
        if (!$filiere) {
            return new JsonResponse([
                'status' => 'Filiere_NOT_FOUND',
                'message' => "Filiere with ID $id not found."
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(FiliereType::class, $filiere);
        $form->submit($data);
        
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $em->flush();
    
                return new JsonResponse([
                    'id' => $filiere->getId(),
                    'nom'=> $filiere->getNom(),
                    'status' => 'Filiere_UPDATED_SUCCESSFULLY',
                ], JsonResponse::HTTP_OK);
            } catch (\Throwable $th) {
                return new JsonResponse([
                    'status' => 'Filiere_UPDATE_FAILED',
                    'error' => $th->getMessage()
                ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        return new JsonResponse([
            'status' => 'FORM_ERROR',
            // 'errors' => $this->getFormErrors($form),
        ], JsonResponse::HTTP_BAD_REQUEST);
    }

}
