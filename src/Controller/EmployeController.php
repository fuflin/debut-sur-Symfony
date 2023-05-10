<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Form\EmployeType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmployeController extends AbstractController
{
    #[Route('/employe', name: 'app_employe')]
    
    public function index(ManagerRegistry $doctrine): Response
    {
        $employes = $doctrine->getRepository(Employe::class)->findAll();
        return $this->render('employe/index.html.twig', [
            'employes' => $employes,
        ]);
    }

    // fonction pour ajouter un employé
    #[Route('/employe/add', name: 'add_employe')]
    #[Route('/employe/{id}/edit', name: 'edit_employe')]

    public function add(ManagerRegistry $doctrine, Employe $employe = null, Request $request): Response
    {
        if(!$employe){ // on ajoute une condition pour configuré l'édition si il n'y a pas d'employe alors on en instancie un nouveau

            $employe = new Employe();
        }

        $form = $this->createForm(EmployeType::class, $employe);
        $form->handleRequest($request);

        // si (on a bien appuyer sur submit && que les infos du formalaire sont conformes au filter input qu'on aura mis)
        if ($form->isSubmitted() && $form->isValid()) {

            $employe = $form->getData(); // hydratation avec données du formulaire / injection des valeurs saisies dans le form 
            $entityManager = $doctrine->getManager(); // on récupère le manager de doctrine
            $entityManager->persist($employe); // équivalent du prepare dans PDO
            $entityManager->flush(); // équivalent de insert into (execute) dans PDO

            return $this->redirectToRoute('app_employe');
        }

        // vue pour afficher le formulaire d'ajout
        return $this->render('employe/add.html.twig', [
            'formAddEmploye' => $form->createView(), //demandé l'utilité
            'edit' => $employe->getId()]); // on ajoute un paramètre qui renvoi un booléen
    }

    

    // fonction pour supprimé un employé
    #[Route('/employe/{id}/delete', name: 'delete_employe')]
    
    public function delete(ManagerRegistry $doctrine, Employe $employe) : Response
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($employe);
        $entityManager->flush();

        return $this->redirectToRoute('app_employe');
    }

    #[Route('/employe/{id}', name: 'show_employe')]

    public function show(Employe $employe): Response
    {
        return $this->render('employe/show.html.twig',['employe' => $employe
        ]);
    }

    
}