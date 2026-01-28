<?php

namespace App\Controller;

use App\Entity\SubCategory;
use App\Form\SubCategoryType;
use App\Repository\SubCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/subcategory')]
class SubcategoryController extends AbstractController
{
    
    #[Route('/', name: 'app_subcategory_index', methods: ['GET'])]
    public function index(SubCategoryRepository $repository): Response
    {
        return $this->render('subcategory/index.html.twig', [
            'subcategories' => $repository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_subcategory_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $subCategory = new SubCategory();
        $form = $this->createForm(SubCategoryType::class, $subCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($subCategory);
            $entityManager->flush();
            return $this->redirectToRoute('app_subcategory_index');
        }

        return $this->render('subcategory/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_subcategory_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SubCategory $subCategory, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SubCategoryType::class, $subCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_subcategory_index');
        }

        return $this->render('subcategory/edit.html.twig', [
            'subCategory' => $subCategory,
            'form' => $form->createView(),
        ]);
    }

    
    #[Route('/{id}/delete', name: 'app_subcategory_delete', methods: ['POST'])]
    public function delete(Request $request, SubCategory $subCategory, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$subCategory->getId(), $request->request->get('_token'))) {
            $entityManager->remove($subCategory);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_subcategory_index');
    }
}