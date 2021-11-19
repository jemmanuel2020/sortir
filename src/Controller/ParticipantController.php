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
     * @Route("/update", name="update")
     */
    public function updateProfil(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $userPasswordHasher
    ): Response
    {
        $participant = $this->getUser();

        $participantForm = $this->createForm(ProfilType::class, $participant);
        $participantForm->handleRequest($request);

        //Si formulaire envoyé et validé
        if ($participantForm->isSubmitted() && $participantForm->isValid())
        {
            //Si le champs mdp est rempli on hash le nouveau mdp
            if($participantForm->get('motPasse')->getData() != null)
            {
                $participant->setMotPasse(
                    $userPasswordHasher->hashPassword(
                        $participant,
                        $participantForm->get('motPasse')->getData()
                    )
                );
            }
            //Sinon on reprends le mot de passe existant
            else
                $participant->setMotPasse($participant->getMotPasse());

            $entityManager->persist($participant);
            $entityManager->flush();

            //$this->addFlash(success, 'Données de profil mises à jour');
            return $this->redirectToRoute('participant_update');
        }

        return $this->render('participant/updateProfil.html.twig', [
            'participant' => $participant,
            'participantForm' => $participantForm->createView()
        ]);
    }

    /**
     * @Route("/read", name="read")
     */
    public function readProfil(): Response
    {
//        if(!$participant)
//            throw $this->createNotFoundException('Ce profil n\'éxiste pas.');
        //todo itération 1 - 2008
    }
}
