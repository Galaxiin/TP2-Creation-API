<?php

namespace App\Controller\Api;

use App\Repository\GenreRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Annotation\Groups;

class GenreController extends AbstractController
{
    /**
     * @Route("/api/genres", name="api_genres", methods="GET")
     */
    public function liste_genres(SerializerInterface $serializer, GenreRepository $repo)
    {
        $genres=$repo->findAll();
        $resultat=$serializer->serialize($genres, 'json',[
            'groups' => ['ListeComplexeGenre']
        ]);
        
        return new JsonResponse($resultat,200,[],true);
    }
}
