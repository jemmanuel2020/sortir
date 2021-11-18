<?php

namespace App\Controller;

use App\Form\ProfilType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/participant", name="participant_")
 */
class ParticipantController extends AbstractController
{
    /**
     * @Route("/edit", name="edit")
     */
    public function editProfil(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $userPasswordHasher
    ): Response
    {
        $participant = $this->getUser();

        $participantForm = $this->createForm(ProfilType::class, $participant);
        $participantForm->handleRequest($request);

        if ($participantForm->isSubmitted() && $participantForm->isValid())
        {
            if($participantForm->get('motPasse')->getData() != null)
            {
                $participant->setMotPasse(
                    $userPasswordHasher->hashPassword(
                        $participant,
                        $participantForm->get('motPasse')->getData()
                    )
                );
            }
            else
                $participant->setMotPasse($participant->getMotPasse());

            $entityManager->persist($participant);
            $entityManager->flush();

            //$this->addFlash(success, 'Données de profil mises à jour');
            return $this->redirectToRoute('participant_edit');
        }

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
//        if(!$participant)
//            throw $this->createNotFoundException('Ce profil n\'éxiste pas.');
        //todo itération 1 - 2008
    }
}
