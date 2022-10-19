<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/series')]
class SerieController extends AbstractController
{
    #[Route('/', name: 'series_list')]
    public function list(): Response
    {
        $series = [
            [
                'id' => 1,
                'title' => 'Game of thrones'
            ],
            [
                 'id' => 2,
                    'title' => 'Alias'
            ]
        ];
        return $this->render('serie/index.html.twig',[
            'series' => $series
        ]);
    }

    //Route/series/1 series_details details.html.twig
    #[Route('/{id}',  name: 'series_details', requirements: ['id' => '\d+'],)]
    public function detail(int $id): Response
    {
        //TODO: Récupérer la série à afficher en BDD
        return $this->render('serie/details.html.twig',[
            'id' => $id
        ]);
    }



    //Route/series/new series_new new.html.twig
    #[Route('/new', name: 'series_new')]
    public function new(): Response
    {
        return $this->render('serie/new.html.twig');
    }
}
