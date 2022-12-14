<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
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
 //       $series = $serieRepository->findBy(array(),array('firstAirDate' => 'DESC', 'name'=> 'ASC'));
//        $series = $serieRepository->findAllByYear(2019);
             $series = $serieRepository->findAllWithSeasons();
       // $series = $serieRepository->findAllBeetwenYear(new \DateTime('2019-01-01'), new \DateTime('2020-01-01'));
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

        if ($details === null) {
            throw $this->createNotFoundException('Page not found');
        }

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
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $em, FileUploader $fileUploader): Response
    {
        $serie = new Serie();
        $serie->setDateCreated(new \DateTime()); // Ou utiliser les LifeCycleCallbacks de Doctrine
        $serieForm = $this->createForm(SerieType::class, $serie);

        // Récupération des données pour les insérer dans l'objet $serie
        $serieForm->handleRequest($request);
        dump($serie);

        // Vérifier si l'utilisateur est en train d'envoyer le formulaire
        if ($serieForm->isSubmitted() && $serieForm->isValid()) {
            //l'utilisateur peut voir le formulaire mais pas le valider
            //$this->denyAccessUnlessGranted('ROLE_ADMIN');

            //uploader nos image
            /** @var  UploadedFile $backdropImage */
            $backdropImage = $serieForm->get('backdropFile')->getData();
            if ($backdropImage){
                $backdrop = $fileUploader->upload($backdropImage, '/backdrop');
                $serie->setBackdrop($backdrop);
            }

            /** @var UploadedFile $posterImage */
            $posterImage = $serieForm->get('posterFile')->getData();
            if ($posterImage){
                $poster = $fileUploader->upload($posterImage, '/posters/series');
                $serie->setPoster($poster);
            }

            // Enregistrer la nouvelle série en BDD
            $em->persist($serie);
            $em->flush();

            $this->addFlash('success', 'la série a bien été créée !');

            // Rediriger l'internaute vers la liste des séries
            return $this->redirectToRoute('series_list');
        }

        return $this->render('serie/new.html.twig', [
            'serieForm' => $serieForm->createView()
        ]);
    }
}
