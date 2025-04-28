<?php

// src/Controller/CategoriesController.php

namespace App\Controller;

use App\Entity\Categories;
use App\Form\CategorieType;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CategoriesController extends AbstractController
{
    // Display all categories (List)
    #[Route('/admin/categories', name: 'admin_categories')]
    public function index(CategoriesRepository $rep, PaginatorInterface $paginator, Request $request): Response
    {
        // Fetch all categories using findAll
        $query = $rep->findAll();

        // Paginate the results
        $categories = $paginator->paginate(
            $query, /* query, not result */
            $request->query->getInt('page', 1), /* page number */
            10 /* limit per page */
        );

        // Render the template with the paginated categories
        return $this->render('categories/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    // Create a new category
    #[Route('/admin/categories/create', name: 'admin_categories_create')]
    public function create(EntityManagerInterface $em, Request $request): Response
    {
        $categorie = new Categories();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($categorie);
            $em->flush();
            return $this->redirectToRoute('admin_categories');
        }

        return $this->render('categories/create.html.twig', ['form' => $form->createView()]);
    }

    // Show details of a specific category
    #[Route('/admin/categories/show/{id}', name: 'admin_categories_show')]
    public function show(Categories $categorie): Response
    {
        if (!$categorie) {
            throw $this->createNotFoundException('Category not found');
        }

        return $this->render('categories/show.html.twig', [
            'categorie' => $categorie
        ]);
    }

    // Edit an existing category
    #[Route('/admin/categories/edit/{id}', name: 'admin_categories_edit')]
    public function edit($id, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Fetch the category item
        $categorie = $entityManager->getRepository(Categories::class)->find($id);
        if (!$categorie) {
            throw $this->createNotFoundException('Category not found');
        }

        // Create the form for editing
        $form = $this->createForm(CategorieType::class, $categorie);

        // Handle the form submission
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the changes to the database
            $entityManager->flush();

            // Redirect to the admin categories page after successful edit
            return $this->redirectToRoute('admin_categories');
        }

        // Render the edit form template
        return $this->render('categories/edit.html.twig', [
            'form' => $form->createView(),
            'categorie' => $categorie,
        ]);
    }

    // Delete a category
    #[Route('/admin/categories/delete/{id}', name: 'admin_categories_delete')]
    public function delete($id, EntityManagerInterface $entityManager): Response
    {
        // Find the category by ID
        $categorie = $entityManager->getRepository(Categories::class)->find($id);
        if (!$categorie) {
            throw $this->createNotFoundException('Category not found');
        }

        // Remove the category from the database
        $entityManager->remove($categorie);
        $entityManager->flush();

        // Redirect back to the categories list after deletion
        return $this->redirectToRoute('admin_categories');
    }




}
