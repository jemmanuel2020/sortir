<?php

namespace App\Controller;


use App\Form\FiltreType;
use App\Modele\Modele;
use App\Repository\SortieRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_home")
     */
    public function home(
        Request $request,
        SortieRepository $sortieRepository
    ) : Response
    {
        $modele = new Modele();
        $filtreForm = $this->createForm(FiltreType::class, $modele);;
        $filtreForm->handleRequest($request);
        //dump($modele);
        //dump($filtreForm->isSubmitted());

        if ($filtreForm->isSubmitted() && $filtreForm->isValid()) {
            //$data = $filtreForm->getData();

            //$sorties = $sortieRepository->findByFiltre($modele);

            return $this->redirectToRoute('main_home');
        }
        else {
            //Affichage de la liste
            $sorties = $sortieRepository->findAll();
        }

        return $this->render('main/home.html.twig', [
            'filtreForm' => $filtreForm->createView(),
            "sorties" => $sorties
        ]);


    }

}