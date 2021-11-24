<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    public function createSortie(
        Request $request,
        SortieRepository $sortieRepository,
        EtatRepository $etatRepository,
        EntityManagerInterface $entityManager
    ): Response
    {
        $organisateur = $this->getUser();


        //Si aucune sortie n'est enregistrée pour cet organisateur

        $sortie = new Sortie();
        $sortie->setCampus($organisateur->getCampus());
        $sortie->setOrganisateur($organisateur);


        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $sortieForm->handleRequest($request);

        //Si formulaire envoyé et validé
        if ($sortieForm->isSubmitted() && $sortieForm->isValid())
        {
            $sortie->setEtat($etatRepository->find(1));

            $entityManager->persist($sortie);
            $entityManager->flush();

            return $this->redirectToRoute('main_home');
        }

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
