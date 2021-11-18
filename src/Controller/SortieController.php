<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\SortieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sortie", name="sortie_")
 */
class SortieController extends AbstractController
{
    /**
     * @Route("/create", name="create")
     */
    public function createSortie(Request $request): Response
    {
        $organisateur = $this->getUser();

        $sortie = new Sortie();
        $sortie->setCampus($organisateur->getCampus());

        $sortieForm = $this->createForm(SortieType::class, $sortie);

        //todo traiter formulaire

        return $this->render('sortie/createSortie.html.twig', [
            'sortieForm' => $sortieForm->createView()
        ]);
    }

    /**
     * @Route("/update", name="update")
     */
    public function updateSortie(): Response
    {
        return $this->render('sortie/createSortie.html.twig', [
            'controller_name' => 'SortieController',
        ]);
    }

    /**
     * @Route("/read", name="read")
     */
    public function readSortie(): Response
    {
        return $this->render('sortie/createSortie.html.twig', [
            'controller_name' => 'SortieController',
        ]);
    }
}
