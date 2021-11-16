<?php

namespace App\Controller;

use App\Form\ProfilType;
use App\Repository\ParticipantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParticipantController extends AbstractController
{
    /**
     * @Route("/participant/{pseudo}", name="participant_profil")
     */
    public function profil(string $pseudo, ParticipantRepository $participantRepository): Response
    {
        $participant = $participantRepository->findOneBy(['pseudo' => $pseudo]);

        if(!$participant)
            throw $this->createNotFoundException('Ce profil n\'éxiste pas.');

        if($participant == $this->getUser())
        {
            $participantForm = $this->createForm(ProfilType::class, $participant);

            //todo traiter formulaire

            return $this->render('participant/profil.html.twig', [
                'participant' => $participant,
                'participantForm' => $participantForm->createView()
            ]);
        }
        else
        {
            $participantForm = $this->createForm(ProfilType::class, $participant);
            return $this->render('participant/profil.html.twig', [
                'participant' => $participant,
                'participantForm' => $participantForm->createView()
            ]);
        }
    }
}
