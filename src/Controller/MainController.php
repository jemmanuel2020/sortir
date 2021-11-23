<?php

namespace App\Controller;


use App\Form\FiltreType;
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
    public function home(Request $request) : Response
    {
        $modele = new Modele();
        $filtreForm = $this->createForm(FiltreType::class, $modele, [
            'data_class' => Modele::class,
        ]);;
        $filtreForm->handleRequest($request);
        dump($modele);
        dump($filtreForm->isSubmitted());

        if ($filtreForm->isSubmitted() && $request->isXmlHttpRequest()) {
            $data = $filtreForm->getData();
        }

        return $this->render('main/home.html.twig', [
            'filtreForm' => $filtreForm->createView()
        ]);
    }

}