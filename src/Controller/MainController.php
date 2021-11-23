<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\php;
use App\Modele\Modele;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #une methode par page
    /**
     * @Route("/", name="main_home")
     */
    public function home() : Response
    {
        $modele = new Modele();
        //dump($modele);
        $filtreForm = $this->createForm(php::class, $modele);
        //dump($modele);

        //$filtreForm->handleRequest($request);

        return $this->render('main/home.html.twig', [
            'filtreForm' => $filtreForm->createView()
        ]);
    }

}