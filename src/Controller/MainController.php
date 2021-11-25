<?php

namespace App\Controller;


use App\Entity\Participant;
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
        //$participant = new Participant();
        $participant = $this->getUser();
        $modele = new Modele();
        //Filtres activés par défaut
        $modele->setNomCampus($participant->getCampus());
        $modele->setOrganisateur(true);

        $filtreForm = $this->createForm(FiltreType::class, $modele);;
        $filtreForm->handleRequest($request);


        if ($filtreForm->isSubmitted() && $filtreForm->isValid()) {
            $sorties = $sortieRepository->findByFiltre($modele);
        }
        else {
            //Affichage de la liste
            $sorties = $sortieRepository->findByFiltre($modele);
        }

        return $this->render('main/home.html.twig', [
            'filtreForm' => $filtreForm->createView(),
            "sorties" => $sorties
        ]);


    }

}