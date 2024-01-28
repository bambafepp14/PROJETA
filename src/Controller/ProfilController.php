<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfilCandidatType;
use App\Form\ProfilRecruteurType;
use App\Repository\AnnonceRepository;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Name;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profil")
 */
class ProfilController extends AbstractController
{
    /**
     * @Route("/candidat/profil", name="app_profil_candidat")
     */
    public function profilCandidat(UserPasswordHasherInterface $passwordHasher, Request $request, EntityManagerInterface $entityManager, RoleRepository $roleRepository, UserRepository $userRepository, NotifierInterface $notifier): Response
    {
//        Création d'un objet User pour mapper avec le formulaire
        $userForm = new User();

//        Création d'un objet formulaire associé à l'objet user
        $form = $this->createForm(ProfilCandidatType::class, $userForm);

//      attente dans le cas d'une soumission de formulaire
        $form->handleRequest($request);

//      on regarde si l'utilisateur a cliqué sur le bouton enregistrer et si form correct
        if ($form->isSubmitted() && $form->isValid()) {
            //récupération des données du user qui est connecté
            $email = $this->getUser()->getUserIdentifier();
            // ici je recupere id, email, mdp crypté, roles
            $userInDatabase = $userRepository->findOneBy(array("email" => $email));

            // Récupération des données du formulaire
            $userForm = $form->getData();

            $userInDatabase->setNom($userForm->getNom());
            $userInDatabase->setPrenom($userForm->getPrenom());

            // Préparation de la requête avant insertion en base
            $entityManager->persist($userInDatabase);

            //Execution de la requête avec insertion en base
            $entityManager->flush();

            // Envoie d'un message pour notifier la mise a jour du compte du user
            $notifier->send(new Notification("Votre compte à bien été mis à jour", ['browser']));

            // Redirection vers la liste des annonces
            return $this->redirectToRoute('app_annonces_liste');
        }

//        Envoie du formulaire à la vue pour affichage
        return $this->render('profil/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/recruteur/profil", name="app_profil_recruteur")
     */
    public function ProfilRecruteur(UserPasswordHasherInterface $passwordHasher, Request $request, EntityManagerInterface $entityManager, RoleRepository $roleRepository, UserRepository $userRepository, NotifierInterface $notifier): Response
    {
//      Création d'un objet User pour mapper avec le formulaire
        $userForm = new User();

//      Création d'un objet formulaire associé à l'objet user
        $form = $this->createForm(ProfilRecruteurType::class, $userForm);

//      attente dans le cas d'une soumission de formulaire
        $form->handleRequest($request);

//      on regarde si l'utilisateur a cliqué sur le bouton enregistrer et si form correct
        if ($form->isSubmitted() && $form->isValid()) {
            //récupération des données du user qui est connecté
            $email = $this->getUser()->getUserIdentifier();
            // ici je recupere id, email, mdp crypté, roles
            $userInDatabase = $userRepository->findOneBy(array("email" => $email));

            // Récupération des données du formulaire
            $userForm = $form->getData();

            $userInDatabase->setEntreprise($userForm->getEntreprise());
            $userInDatabase->setAdresseEntreprise($userForm->getAdresseEntreprise());

            // Préparation de la requête avant insertion en base
            $entityManager->persist($userInDatabase);

            //Execution de la requête avec insertion en base
            $entityManager->flush();

            // Envoie d'un message pour notifier la mise a jour du compte du user
            $notifier->send(new Notification("Votre compte à bien été mis à jour", ['browser']));

            // Redirection vers la liste des annonces
            return $this->redirectToRoute('app_annonces_liste');
        }

//        Envoie du formulaire à la vue pour affichage
        return $this->render('profil/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
