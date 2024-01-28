<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnoncesPublicationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnoncesPublicationController extends AbstractController
{
    /**
     * @Route("/annonce/publication", name="app_annonces_publication")
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
//        Création d'un objet annonce pour mapper avec le formulaire
        $annonce = new Annonce();

//        Création d'un objet formulaire associté à l'objet annonce
        $form = $this->createForm(AnnoncesPublicationType::class, $annonce);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération des données du formulaire dans l'objet annonce
            $annonce = $form->getData();

            // Préparation de la requête avant insertion en base
            $entityManager->persist($annonce);

            //Execution de la requête avec insertion en base
            $entityManager->flush();

            // Redirection vers la liste des annonces
            return $this->redirectToRoute('app_annonces_liste');
        }


//        Envoie du formulaire à la vue pour affichage
        return $this->render('annonces_publication/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
