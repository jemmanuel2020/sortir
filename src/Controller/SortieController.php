<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\EtatRepository;
use App\Repository\LieuRepository;
use App\Repository\SortieRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        EtatRepository $etatRepository,
        EntityManagerInterface $entityManager
    ): Response
    {
        $organisateur = $this->getUser();

        $sortie = new Sortie();
        $sortie->setCampus($organisateur->getCampus());
        $sortie->setOrganisateur($organisateur);

        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $sortieForm->handleRequest($request);

        //Si formulaire envoyé et validé
        if ($sortieForm->isSubmitted() && $sortieForm->isValid())
        {
            if($sortieForm->get('publier')->isClicked())
                $sortie->setEtat($etatRepository->find(2));
            else
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
     * Returns a JSON string with the Lieu of the Ville with the providen id.
     *
     * @Route("/listLieux", name="listLieux")
     * @param Request $request
     * @param LieuRepository $lieuRepository
     * @return JsonResponse
     */
    public function listLieuOfVilleAction(
        Request $request,
        LieuRepository $lieuRepository,
        VilleRepository $villeRepository
    )
    {
        // Search the lieu that belongs to the ville with the given id as GET parameter "ville_id"
        $ville = $villeRepository->find($request->query->get("ville_id"));
        $lieux = $lieuRepository->findLieuByCity($ville);

        // Serialize into an array the data that we need, in this case only name and id
        $responseArray = array();
        foreach($lieux as $lieu){
            $responseArray[] = array(
                "id_lieu" => $lieu->getIdLieu(),
                "nom" => $lieu->getNom(),
                "rue" => $lieu->getRue(),
                "latitude" => $lieu->getLatitude(),
                "longitude" => $lieu->getLongitude()
            );
        }

        // Return array with structure of the neighborhoods of the providen city id
        return new JsonResponse($responseArray);
    }

    /**
     * @Route("/update/{idSortie}", name="update")
     */
    public function updateSortie(
        int $idSortie,
        Request $request,
        SortieRepository $sortieRepository,
        EtatRepository $etatRepository,
        EntityManagerInterface $entityManager
    ): Response
    {
        $sortie = $sortieRepository->find($idSortie);

        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $sortieForm->handleRequest($request);

        //Si formulaire envoyé et validé
        if ($sortieForm->isSubmitted() && $sortieForm->isValid())
        {
            if($sortieForm->get('publier')->isClicked())
                $sortie->setEtat($etatRepository->find(2));

            $entityManager->persist($sortie);
            $entityManager->flush();

            return $this->redirectToRoute('main_home');
        }

        return $this->render('sortie/updateSortie.html.twig', [
            'idSortie' => $idSortie,
            'sortieForm' => $sortieForm->createView()
        ]);
    }

    /**
     * @Route("/read/{idSortie}", name="read")
     */
    public function readSortie(
        int $idSortie,
        SortieRepository $sortieRepository
    ): Response
    {
        $sortie = new Sortie();
        $sortie = $sortieRepository->find($idSortie);

        $participants = $sortie->getParticipants();

        return $this->render('sortie/readSortie.html.twig', [
            'idSortie' => $idSortie,
            "participants" => $participants,
            "sortie" => $sortie
        ]);
    }



    /**
     * @Route("/delete/{idSortie}", name="delete")
     */
    public function deleteSortie(int $idSortie, SortieRepository $sortieRepository, EntityManagerInterface $entityManager): Response
    {
        $sortie = $sortieRepository->find($idSortie);
        $entityManager->remove($sortie);
        $entityManager->flush();

        return $this->redirectToRoute('main_home');
    }

    /**
     * @Route("/publier/{idSortie}", name="publier")
     */
    public function publishSortie(
        int $idSortie,
        SortieRepository $sortieRepository,
        EtatRepository $etatRepository,
        EntityManagerInterface $entityManager
    ): Response
    {
        $sortie = $sortieRepository->find($idSortie);
        $sortie->setEtat($etatRepository->find(2));
        $entityManager->persist($sortie);
        $entityManager->flush();

        return $this->redirectToRoute('main_home');
    }

    /**
     * @Route("/inscrire/{idSortie}", name="inscrire")
     */
    public function joinSortie(
        int $idSortie,
        SortieRepository $sortieRepository,
        EntityManagerInterface $entityManager
    ): Response
    {
        $sortie = $sortieRepository->find($idSortie);
        $sortie->addParticipant($this->getUser());
        $entityManager->persist($sortie);
        $entityManager->flush();

        return $this->redirectToRoute('main_home');
    }

    /**
     * @Route("/desinscrire/{idSortie}", name="desinscrire")
     */
    public function leaveSortie(
        int $idSortie,
        SortieRepository $sortieRepository,
        EntityManagerInterface $entityManager
    ): Response
    {
        $sortie = $sortieRepository->find($idSortie);
        $sortie->removeParticipant($this->getUser());
        $entityManager->persist($sortie);
        $entityManager->flush();

        return $this->redirectToRoute('main_home');
    }

    /**
     * @Route("/annuler/{idSortie}", name="annuler")
     */
    public function cancelSortie(
        int $idSortie,
        SortieRepository $sortieRepository,
        EtatRepository $etatRepository,
        EntityManagerInterface $entityManager
    ): Response
    {
        $sortie = $sortieRepository->find($idSortie);

        if (isset($_POST['enregistrer']))
        {
            $sortie->setEtat($etatRepository->find(6));
            $sortie->setInfosSortie(htmlspecialchars($_POST['motif']));

            $entityManager->persist($sortie);
            $entityManager->flush();

            return $this->redirectToRoute('main_home');
        }

        return $this->render('sortie/annulerSortie.html.twig', [
            'sortie' => $sortie,
        ]);
    }
}
