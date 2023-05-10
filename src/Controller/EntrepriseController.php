<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EntrepriseController extends AbstractController
{
    #[Route('/entreprise', name: 'app_entreprise')]
    
    public function index(ManagerRegistry $doctrine): Response
    {
        // récupère les entreprises de la base de données
        $entreprises = $doctrine->getRepository(Entreprise::class)->findAll();
        return $this->render('entreprise/index.html.twig', [
            'entreprises' => $entreprises,
        ]);
    }
    
    // #[Route('/objet concerné/fonction rattachée', name: 'nom de la fonction pour la redirection')]
    #[Route('/entreprise/add', name: 'add_entreprise')]
    #[Route('/entreprise/{id}/edit', name: 'edit_entreprise')]

    public function add(ManagerRegistry $doctrine, Entreprise $entreprise = null, Request $request): Response
    {
        if(!$entreprise){ // on ajoute une condition pour configuré l'édition si il n'y a pas d'entreprise alors on en instancie une nouvelle

            $entreprise = new Entreprise();
        }

        $form = $this->createForm(EntrepriseType::class, $entreprise);
        $form->handleRequest($request);

        // si (on a bien appuyer sur submit && que les infos du formalaire sont conformes au filter input qu'on aura mis)
        if ($form->isSubmitted() && $form->isValid()) {

            $entreprise = $form->getData(); // hydratation avec données du formulaire / injection des valeurs saisies dans le form 
            $entityManager = $doctrine->getManager(); // on récupère le manager de doctrine
            $entityManager->persist($entreprise); // équivalent du prepare dans PDO
            $entityManager->flush(); // équivalent de insert into (execute) dans PDO

            return $this->redirectToRoute('app_entreprise');
        }

        // vue pour afficher le formulaire d'ajout
        return $this->render('entreprise/add.html.twig', [
            'formAddEntreprise' => $form->createView(), // demandé l'utilitée
            'edit' => $entreprise->getId()]);
    }

    #[Route('/entreprise/{id}/delete', name: 'delete_entreprise')]
    
    public function delete(ManagerRegistry $doctrine, Entreprise $entreprise) : Response
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($entreprise);
        $entityManager->flush();

        return $this->redirectToRoute('app_entreprise');
    }

    #[Route('/entreprise/{id}', name: 'show_entreprise')]

    public function show(Entreprise $entreprise): Response
    {
        return $this->render('entreprise/show.html.twig',['entreprise' => $entreprise
        ]);
    }
}
