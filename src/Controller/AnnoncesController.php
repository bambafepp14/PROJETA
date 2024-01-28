<?php

namespace App\Controller;

use App\Repository\AnnonceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class AnnoncesController extends AbstractController
{
    private $security;

    // constructeur de ma classe
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/", name="app_annonces_liste")
     * Affiche la liste de toutes les annonces
     */
    public function index(AnnonceRepository $annonceRepository): Response
    {
        $annonces = $annonceRepository->findAll();

        return $this->render('annonces_liste/index.html.twig', [
            'annonces' => $annonces,
            'titrePage' => 'Liste des annonces'
        ]);
    }

    /**
     * @Route("/details/{id}", name="app_annonces_details")
     * Affiche le détail d'une annonce
     */
    public function detail(AnnonceRepository $annonceRepository, int $id): Response
    {
        $annonce = $annonceRepository->find($id);

        return $this->render('annonces_liste/detail.html.twig', [
            'titrePage' => 'Détail de l\'annonce',
            'annonce' => $annonce,
        ]);
    }

    /**
     * @Route("/{id}/postuler", name="app_annonces_postuler")
     * Postuler à une annonce
     */
    public function postuler(AnnonceRepository $annonceRepository, int $id, NotifierInterface $notifier): Response
    {
        $user = $this->security->getUser();
        $annonce = $annonceRepository->find($id);

        if(!empty($user) && !empty($annonce)){
            $annonce->addCandidat($user);
            $annonceRepository->add($annonce);
            // notification pour indiquer que le user a bien postulé à l'annonce
            $notifier->send(new Notification("Votre candidature a bien été retenu", ['browser']));
        }

        return $this->render('annonces_liste/detail.html.twig', [
            'titrePage' => 'Détail de l\'annonce',
            'annonce' => $annonce,
        ]);
    }
}
