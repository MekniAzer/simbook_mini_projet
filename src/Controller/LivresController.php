<?php

namespace App\Controller;

use App\Entity\Livres;
use App\Form\LivresType;
use App\Repository\LivresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LivresController extends AbstractController
{
    #[Route('/admin/livres/delete/{id}', name: 'app_livres_delete')]
    public function delete2(Livres $livre, EntityManagerInterface $em): Response
{  //$livre = $rep->find($id);
    $em->remove($livre);
    $em->flush();
    dd($livre);
    return $this->redirectToRoute('app_livres_all');
}




    #[Route('/admin/livres', name: 'app_livres_all')]

    public function all(LivresRepository $rep,PaginatorInterface $paginator,Request $request): Response
    {  $query=$rep->findAll();
        $livres = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /* page number */
            10 /* limit per page */
        );
        return $this->render('livres/all.html.twig', ['livres'=>$livres]);}



    #[Route('/admin/livres/show2', name: 'app_livres_show2')]

    public function show2(LivresRepository $rep): Response
    {  $livre=$rep->findOneBy(['titre'=>'titre 1']);
        dd($livre);}

//paramconverter
    #[Route('/admin/livres/show/{id}', name: 'app_livres_show')]

public function show(Livres $livre): Response
{
    if(!$livre)
    {throw $this->createNotFoundException("Le livre {$livre->getId()} n'existe pas.");}

    return $this->render('livres/show.html.twig', ['livre'=>$livre]);}

    #[Route('/admin/livres/create', name: 'app_livres_create')]
    public function create2(EntityManagerInterface $em): Response
    { $livre=new Livres();
        $d=new \DateTime("2025-01-01");
        $livre->setTitre("titre 1")
               ->setSlug("titre-1")
               ->setIsbn("111-111-1111-1111")
               ->setImage("https://picsum.photos/200/?id=5")
               ->setResume("resumeb,dhdsfhdfhsdljflfjdlfjlkqv")
              ->setEditeur("Eyrolles")
              ->setDateEdition($d)
              ->setPrix(100);
        $em->persist($livre);
        $em->flush();
        dd($livre);

        return $this->redirectToRoute('app_livres_all');

    }

    #[Route('/admin/livres/edit/{id}', name: 'app_livres_edit')]
    public function edit($id, Request $request, EntityManagerInterface $entityManager)
    {
        // Fetch the livre item
        $livre = $entityManager->getRepository(Livres::class)->find($id);
        if (!$livre) {
            throw $this->createNotFoundException('Livre not found');
        }

        // Create the form for editing
        $form = $this->createForm(LivresType::class, $livre);

        // Handle the form submission
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the changes to the database
            $entityManager->flush();

            // Redirect to the admin page after successful edit
            return $this->redirectToRoute('app_livres_all');
        }

        // Render the edit form template
        return $this->render('livres/edit.html.twig', [
            'form' => $form->createView(),
            'livre' => $livre,
        ]);
    }

    #[Route('/admin/livres/delete/{id}', name: 'app_livres_delete')]
    public function delete($id, EntityManagerInterface $entityManager)
    {
        // Find the item by ID
        $livre = $entityManager->getRepository(Livres::class)->find($id);
        if (!$livre) {
            throw $this->createNotFoundException('Livre not found');
        }

        // Remove the item from the database
        $entityManager->remove($livre);
        $entityManager->flush();

        // Redirect back to the list page after deletion
        return $this->redirectToRoute('app_livres_all');
    }


    #[Route('/admin/livres/create', name: 'app_livres_create')]
    public function create(EntityManagerInterface $em, Request $request): Response
    {
        // Create a new Livre entity instance
        $livre = new Livres();

        // Create the form for Livre using LivresType
        $form = $this->createForm(LivresType::class, $livre);

        // Handle the form submission
        $form->handleRequest($request);

        // Check if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the Livre entity to the database
            $em->persist($livre);
            $em->flush();

            // Redirect to the list of Livres after successful creation
            return $this->redirectToRoute('app_livres_all');
        }

        // Render the form view in the template
        return $this->render('livres/create.html.twig', ['form' => $form->createView()]);
    }


}
