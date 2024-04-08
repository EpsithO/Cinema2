<?php

namespace App\Controller;

use App\Entity\Film;
use App\Repository\FilmRepository;
use App\Repository\SeanceRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;


/**
 * @method getDoctrine()
 * @method get(string $string)
 */
#[Route('/api')]
class FilmController extends AbstractController
{
    #[Route('/filmsJson', name: 'api_films', methods: ['GET'])]
    public function index(FilmRepository $repository, SerializerInterface $serializer): Response
    {
        $films = $repository->findFilmAffiche();
        $filmsSerialized = $serializer->serialize($films, 'json', ['groups' => 'film_affiche']);
        return new Response($filmsSerialized, 200, [
            'content-type' => 'application/json',
        ]);
    }

    #[Route('/films/{id}', name: 'film_detail')]
    public function show(int $id, FilmRepository $filmRepository, SeanceRepository $seanceRepository,SerializerInterface $serializer): Response
    {
        $film = $filmRepository->findfilmDetail($id);
        $seance = $seanceRepository->getSeanceByFilm($id);


        //serialisation en json
        $filmsSerialized = $serializer->serialize($film, 'json', ['groups' => 'film_detail']);

        //retorun le json
        return new Response($filmsSerialized, 200, [
            'content-type' => 'application/json',
        ]);

    }

    #[Route('/films', name: 'film_list')]
    public function allFilm(Request $request, FilmRepository $filmRepository): Response
    {
        $page = $request->query->getInt('page', 1); // Récupérer le numéro de la page depuis la requête, par défaut 1 si non spécifié
        $filmsPerPage = 10;

        $offset = ($page - 1) * $filmsPerPage;

        $films = $filmRepository->findBy([], null, $filmsPerPage, $offset);

        $totalFilms = $filmRepository->count([]);
        $totalPages = ceil($totalFilms / $filmsPerPage);

        //serialiser en json
        $filmsSerialized = $this->get('serializer')->serialize($films, 'json', ['groups' => 'film_list']);

        //retourner le json

        return new Response($filmsSerialized, 200, [
            'content-type' => 'application/json',
        ]);
    }
}
