<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfilCandidatType;
use App\Form\ProfilRecruteurType;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/inscription")
 */
class InscriptionController extends AbstractController
{
    /**
     * @Route("/", name="app_inscription_menu")
     */
    public function index(): Response
    {
        return $this->render('inscription/index.html.twig');
    }

    /**
     * @Route("/candidat/new", name="app_inscription_candidat")
     */
    public function newCandidat(UserPasswordHasherInterface $passwordHasher, Request $request, EntityManagerInterface $entityManager, RoleRepository $roleRepository): Response
    {
//        Création d'un objet User pour mapper avec le formulaire
        $user = new User();

//        Création d'un objet formulaire associé à l'objet user
        $form = $this->createForm(ProfilCandidatType::class, $user);

//      attente dans le cas d'une soumission de formulaire
        $form->handleRequest($request);

//      on regarde si l'utilisateur a cliqué sur le bouton enregistrer et si form correct
        if ($form->isSubmitted() && $form->isValid()) {
            var_dump("enregistrement en base");
            // Récupération des données du formulaire dans l'objet annonce
            $user = $form->getData();

            // Mot de passe en clair
            $plaintextPassword = $form['password']->getData();

            // on hash le mot de passe
            $hashedPassword = $passwordHasher->hashPassword($user, $plaintextPassword);

            // J'attribue le mot de passe hashé à l'utilisateur
            $user->setPassword($hashedPassword);

            //Création du role candidat pour l'utilisateur avant insertion en base
            $roles = ['ROLE_CANDIDAT'];

            // J'attribue bom role à l'objet utilisateur
            $user->setRoles($roles);

            // Préparation de la requête avant insertion en base
            $entityManager->persist($user);

            //Execution de la requête avec insertion en base
            $entityManager->flush();

            // Redirection vers la liste des annonces
            return $this->redirectToRoute('app_annonces_liste');
        }

//        Envoie du formulaire à la vue pour affichage
        return $this->render('inscription/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/recruteur/new", name="app_inscription_recruteur")
     */
    public function newRecruteur(UserPasswordHasherInterface $passwordHasher, Request $request, EntityManagerInterface $entityManager, RoleRepository $roleRepository): Response
    {
//        Création d'un objet User pour mapper avec le formulaire
        $user = new User();

//        Création d'un objet formulaire associé à l'objet user
        $form = $this->createForm(ProfilRecruteurType::class, $user);

//      attente dans le cas d'une soumission de formulaire
        $form->handleRequest($request);

//      on regarde si l'utilisateur a cliqué sur le bouton enregistrer et si form correct
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération des données du formulaire dans l'objet annonce
            $user = $form->getData();

            // Mot de passe en clair
            $plaintextPassword = $form['password']->getData();

            // on hash le mot de passe
            $hashedPassword = $passwordHasher->hashPassword($user, $plaintextPassword);

            // J'attribue le mot de passe hashé à l'utilisateur
            $user->setPassword($hashedPassword);

            //Création du role recruteur pour l'utilisateur avant insertion en base
            $roles = ['ROLE_RECRUTEUR'];

            // J'attribue bom role à l'objet utilisateur
            $user->setRoles($roles);

            // Préparation de la requête avant insertion en base
            $entityManager->persist($user);

            //Execution de la requête avec insertion en base
            $entityManager->flush();

            // Redirection vers la liste des annonces
            return $this->redirectToRoute('app_login');
        }

//        Envoie du formulaire à la vue pour affichage
        return $this->render('inscription/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
