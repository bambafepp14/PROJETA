<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/annonce")
 */
class AnnonceAdminController extends AbstractController
{
    /**
     * @Route("/", name="app_annonce_admin_index", methods={"GET"})
     */
    public function index(AnnonceRepository $annonceRepository): Response
    {
        return $this->render('annonce_admin/index.html.twig', [
            'annonces' => $annonceRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_annonce_admin_new", methods={"GET", "POST"})
     */
    public function new(Request $request, AnnonceRepository $annonceRepository): Response
    {
        $annonce = new Annonce();
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $annonceRepository->add($annonce);
            return $this->redirectToRoute('app_annonce_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('annonce_admin/new.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_annonce_admin_show", methods={"GET"})
     */
    public function show(Annonce $annonce): Response
    {
        return $this->render('annonce_admin/show.html.twig', [
            'annonce' => $annonce,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_annonce_admin_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Annonce $annonce, AnnonceRepository $annonceRepository): Response
    {
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $annonceRepository->add($annonce);
            return $this->redirectToRoute('app_annonce_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('annonce_admin/edit.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_annonce_admin_delete", methods={"POST"})
     */
    public function delete(Request $request, Annonce $annonce, AnnonceRepository $annonceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$annonce->getId(), $request->request->get('_token'))) {
            $annonceRepository->remove($annonce);
        }

        return $this->redirectToRoute('app_annonce_admin_index', [], Response::HTTP_SEE_OTHER);
    }
}
