<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Repository\SerieRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpCache\ResponseCacheStrategy;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/test')]
class TestController extends AbstractController
{
    #[Route('/', name: 'app_test')]
    public function index(): Response
    {
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }

    #[Route('/create')]
    public function create(ManagerRegistry $doctrine): Response
    {
        //obsolète : $entityManager = $this->getDoctrine() ->getManager();
        $entityManager = $doctrine->getManager();

        $you = new Serie();
        $you->setName('You');
        $you->setOverview('Le gestionnaire intelligent d\'une librairie compte sur ses connaissances informatique pour que la femme de ses rêves tombe amoureuse de lui.');
        $you->setStatus('ended');
        $you->setVote(7.7);
        $you->setPopularity(117);
        $you->setGenres('Policier / Drame / Romantique');
        $you->setFirstAirDate(new \DateTime('2018-09-09'));
        $you->setLastAirDate(new \DateTime('2022-10-20'));
        $you->setBackdrop('you.jpg');
        $you->setPoster('you.jpg');
        $you->setTmdbId(78191);
        $you->setDateCreated(new \DateTime());

        $entityManager->persist($you);
        $entityManager->flush();

        // ou $entityManager->getRepository(serie::class)->save($you, true);

        return new Response('la série You a été créée');
    }

    #[Route ('/update')]
    public function update(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        //Récupérer le repository de Serie : 2 syntaxes possible :
        //$serieRepository = $entityManager->getRepository(App\Entity\Serie);
        $serieRepository = $entityManager->getRepository(Serie::class);

        //Récupérer la série You : 2 syntaxes possibles :
        //attention, c'est une requête SQL mais nous somme en PHP donc il faut bien écrire les noms de colonnes en camelcase
        //conformément aux attributs :
        $you=$serieRepository->findOneBy(['name'=> 'you']);

        $you->setOverview('Test série You');

        $entityManager->flush();

        return new Response('La série a été modifiée');
    }

    #[Route('/delete')]
    public function delete(SerieRepository $serieRepository): Response
    {
        $you = $serieRepository->findOneByName('you');

        if ($you !=null){
            // nouveau : on peut passer directement par le repo pour remove
            $serieRepository->remove($you, true);

//        sinon avant il fallait récupérer l'entityManager :
//        $entityManager->remove($you);
//        $entityManager->flush();
            $response = new Response('La série You a été supprimée');
    }else{
            $response = new Response('La série que vous voulez supprimer n\'existe pas !!');
        }
         return $response;

        //Ou alors on peut utiliser le try/catch :
                        /*
                try {
                    $serieRepository->remove($you, true);
                } catch (\TypeError) {
                    return new Response('La série You n\'existe pas !');
                }
                */
    }
}
