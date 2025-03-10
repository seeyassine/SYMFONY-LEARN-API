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

    // Create Method
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



    // Edit method 
    #[Route('/edit/{id}', name: 'app_filiere_edit', methods: 'PUT')]
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

    #[Route('/delete/{id}', name: 'app_filiere_delete', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $em): JsonResponse
    {
        $filiere = $em->getRepository(Filiere::class)->find($id);

        if (!$filiere) {
            return new JsonResponse([
                'status' => 'Filiere_NOT_FOUND',
                'message' => "Filiere with ID $id not found."
            ], JsonResponse::HTTP_NOT_FOUND);
        }
        
        try {

            $em->remove($filiere);
            $em->flush();
    
            return new JsonResponse([
                'id' => $filiere->getId(),
                'nom'=> $filiere->getNom(),
                'status' => 'Filiere_REMOVED_SUCCESSFULLY',
            ], JsonResponse::HTTP_OK);
        } catch (\Throwable $th) {
            return new JsonResponse([
                'status' => 'Filiere_REMOVED_FAILED',
                'error' => $th->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
  
    }


    // ajouter save() method in FiliereRepository.php
    #[Route('/ajouter', name: 'app_filiere_ajouter', methods: 'POST')]
    public function ajouter(Request $request, FiliereRepository $filiereRepository): JsonResponse
    { 
        $data = json_decode($request->getContent(), true);

        $filiere = new Filiere();

        $form = $this->createForm(FiliereType::class, $filiere);
        $form->submit($data);

        if($form->isSubmitted() && $form->isValid()){
           
           $filiereRepository->save($filiere, true);
           
            return new JsonResponse([
                'id' => $filiere->getId(),
                'status' => 'CREATED_SUCCESSFULLY',
            ], JsonResponse::HTTP_CREATED);
        }
       
        return new JsonResponse([
            'status' => 'FORM_ERROR',
            // 'errors' => $this->getFormErrors($form),
        ], JsonResponse::HTTP_BAD_REQUEST);

    }


    // use method  save in FiliereRepository.php
    #[Route('/modifier/{id}', name: 'app_filiere_edit', methods: ['PUT'])]
    public function modifier(
        int $id,
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
                $filiereRepository->save($filiere, true);
    
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



    #[Route('/show/{id}', name: 'product_show', methods: ['GET'])]
    public function show(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $filiere = $entityManager->getRepository(Filiere::class)->find($id);

        if (!$filiere) {
            return new JsonResponse([
                'status' => 'Filiere_NOT_FOUND',
                'message' => "Filiere with ID $id not found."
            ], JsonResponse::HTTP_NOT_FOUND);
        }

          return new JsonResponse([
                    'id' => $filiere->getId(),
                    'nom'=> $filiere->getNom(),
                ], JsonResponse::HTTP_OK);
    }


    //show all
    #[Route('/show', name: 'product_show_all', methods: ['GET'])]
    public function showAll(EntityManagerInterface $entityManager): JsonResponse
    {
        $filieres = $entityManager->getRepository(Filiere::class)->findAll();

        if (!$filieres) {
            return new JsonResponse([
                'status' => 'No Filiere_FOUND',
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = array_map(function ($filiere) {
            return [
                'id' => $filiere->getId(),
                'nom' => $filiere->getNom(),
            ];
        }, $filieres);
    
        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }

    //show by nom
    #[Route('/show/{nom}', name: 'product_show', methods: ['GET'])]
    public function showByNom(EntityManagerInterface $entityManager, string $nom): JsonResponse
    {
        $filieres = $entityManager->getRepository(Filiere::class)->findBy(
            ['nom' => $nom],
            ['id' => 'DESC'] // ACS
        );

        if (!$filieres) {
            return new JsonResponse([
                'status' => 'Filiere_NOT_FOUND',
                'message' => "Filiere with ID $nom not found."
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = array_map(function ($filiere) {
            return [
                'id' => $filiere->getId(),
                'nom' => $filiere->getNom(),
            ];
        }, $filieres);
    
        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }

    //edits method work
    #[Route('/edits/{id}', name: 'app_filiere_edit', methods: ['PUT'])]
    public function edits(
        int $id,
        EntityManagerInterface $em,
        Request $request
    ): JsonResponse {
       
        $filiere = $em->getRepository(Filiere::class)->find($id);
        
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
                    'nom' => $filiere->getNom(),
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
        ], JsonResponse::HTTP_BAD_REQUEST);
    }

}
