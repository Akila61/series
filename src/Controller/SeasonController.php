<?php

namespace App\Controller;

use App\Entity\Season;
use App\Form\SeasonType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/season')]
#[IsGranted('ROLE_ADMIN')] //ici cela permet de ne pas donner l'accés aux routes ci-dessous
class SeasonController extends AbstractController
{
    #[Route('/create', name: 'season_create')]

    public function create(Request $request,EntityManagerInterface $em): Response
    {
        $season = new Season();
        $season->setDateCreated(new \DateTime());
        $seasonForm = $this->createForm(SeasonType::class,$season);

        $seasonForm->handleRequest($request);

        if($seasonForm->isSubmitted() && $seasonForm->isValid()){
            $em->persist($season);
            $em->flush();

            return $this->redirectToRoute('series_list');
        }

        return $this->render('season/create.html.twig', [
            'seasonForm' => $seasonForm->createView(),
        ]);
    }
}
