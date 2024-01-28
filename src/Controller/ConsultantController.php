<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use App\Form\ConsultantType;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ConsultantController extends AbstractController
{
    /**
     * @Route("/consultant", name="app_consultant")
     */
    public function new(Request $request, EntityManagerInterface $entityManager, RoleRepository $roleRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
//      Création d'un objet User pour mapper avec le formulaire
        $user = new User();

//      Récupération du rôle consultant sous forme d'objet
        $roleConsultant = $roleRepository->find(3);

//      Création d'un objet formulaire associté à l'objet annonce
        $form = $this->createForm(ConsultantType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération des données du formulaire dans l'objet annonce
            $consultant = $form->getData();

            // Mot de passe en clair
            $plaintextPassword = $form['password']->getData();

            // on hash le mot de passe
            $hashedPassword = $passwordHasher->hashPassword($user, $plaintextPassword);

            //attribution du mdp hashé à l'utilisateur avant insertion en base
            $user->setPassword($hashedPassword);

            // Je rajoute le rôle du consultant
            $user->setRoles(array("ROLE_CONSULTANT"));

            // Préparation de la requête avant insertion en base
            $entityManager->persist($user);

            //Execution de la requête avec insertion en base
            $entityManager->flush();

            // Redirection vers la liste des annonces
            return $this->redirectToRoute('app_menu_admin');
        }


//        Envoie du formulaire à la vue pour affichage
        return $this->render('consultant/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
