<?php

namespace App\Controller;

use App\Form\ProfilType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ParticipantController extends AbstractController
{
    /**
     * @Route("/participant/{idParticipant}", name="participant_profil")
     */
    public function profil(
        int $idParticipant,
        Request $request,
        EntityManagerInterface $entityManager,
        ParticipantRepository $participantRepository,
        UserPasswordHasherInterface $userPasswordHasher
    ): Response
    {
        $participant = $participantRepository->find(['idParticipant' => $idParticipant]);

        if(!$participant)
            throw $this->createNotFoundException('Ce profil n\'éxiste pas.');

        $participantForm = $this->createForm(ProfilType::class, $participant);

        //if($participant == $this->getUser())
        //{

            $participantForm->handleRequest($request);

            if ($participantForm->isSubmitted() && $participantForm->isValid())
            {
                $participant->setMotPasse(
                    $userPasswordHasher->hashPassword(
                        $participant,
                        $participantForm->get('motPasse')->getData()
                    )
                );

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($participant);
                $entityManager->flush();

                //$this->addFlash(success, 'Données de profil mises à jour');
                return $this->redirectToRoute('participant_profil', ['idParticipant' => $idParticipant]);
            }


        //}
        //else
        //{
            //todo itération 1 - 2008
        //}

        return $this->render('participant/profil.html.twig', [
            'participant' => $participant,
            'participantForm' => $participantForm->createView()
        ]);
    }

    /**
     * @Route("/see", name="see")
     */
    public function seeProfil(): Response
    {
        //todo itération 1 - 2008
    }
}
