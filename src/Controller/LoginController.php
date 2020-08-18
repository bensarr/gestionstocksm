<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('login/index.html.twig', [
            'message' => 'Mon Premier Login',
        ]);
    }
    /**
     * @Route("/logins", name="logins")
     */
    public function login()
    {
        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }
    /**
     * @Route("/logon", name="logon")
     */
    public function logon()
    {
        return $this->redirectToRoute('accueil');
    }
}
