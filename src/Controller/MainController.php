<?php

namespace App\Controller;

use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #une methode par page
    /**
     * @Route("/", name="main_home")
     */
    public function home() : Response
    {
        return $this->render('main/home.html.twig');
    }
}