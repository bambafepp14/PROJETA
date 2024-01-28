<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MenuAdminController extends AbstractController
{
    /**
     * @Route("/admin", name="app_menu_admin")
     */
    public function index(): Response
    {
        return $this->render('menu_admin/index.html.twig', [
            'controller_name' => 'MenuAdminController',
        ]);
    }
}
