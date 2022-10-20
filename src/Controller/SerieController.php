<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Repository\SerieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/series')]
class SerieController extends AbstractController
{
    #[Route('/', name: 'series_list')]
    public function list(SerieRepository $serieRepository): Response
    {
        //Récupérer les séries dans la BDD :
//        $series = $serieRepository->findAll();
//        $series = $serieRepository->findBy(array(),array('firstAirDate' => 'DESC', 'name'=> 'ASC'));
//        $series = $serieRepository->findAllByYear(2019);

        $series = $serieRepository->findAllBeetwenYear(new \DateTime('2019-01-01'), new \DateTime('2020-01-01'));
            return $this->render('serie/index.html.twig',[
                'series' => $series
            ]);
    }

    //Route/series/1 series_details details.html.twig
    #[Route('/{id}',  name: 'series_details', requirements: ['id' => '\d+'],)]
    public function detail(int $id, SerieRepository $serieRepository): Response
    {
        //TODO: Récupérer la série à afficher en BDD
        $details = $serieRepository->find($id);

        return $this->render('serie/details.html.twig',[
            'id' => $id,
            'details' => $details
        ]);

        // plus rapide :
//        #[Route('/{id}', name: 'series_detail', requirements: ['id' => '\d+'])]
//        public function detail(Serie $serie): Response
//    {
//        return $this->render('serie/details.html.twig', [
//            'details' => $serie
//        ]);
    }

    //Route/series/new series_new new.html.twig
    #[Route('/new', name: 'series_new')]
    public function new(): Response
    {
        return $this->render('serie/new.html.twig');
    }
}
