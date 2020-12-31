<?php

namespace App\Controller\Api;

use App\Entity\Genre;
use App\Repository\GenreRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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

    /**
     * @Route("/api/genres/{id}", name="api_genre_affiche", methods={"GET"})
     */
    public function affiche_genre(Genre $genre, SerializerInterface $serializer)
    {
        $resultat=$serializer->serialize($genre, 'json',[
            'groups' => ['ListeSimpleGenre']
        ]);
        
        return new JsonResponse($resultat,Response::HTTP_OK,[],true);
    }

    /**
     * @Route("/api/genres", name="api_genre_create", methods="POST")
     */
    public function create_genre(Request $request,ObjectManager $manager, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $data=$request->getContent();
        //$genre=new Genre();
        //$serializer->deserialize($data,Genre::class,'json',['object_to_populate'=>$genre])
        $genre=$serializer->deserialize($data,Genre::class,'json');

        //gestion des erreurs de validations    ajouter les assert necessaire !

        // $errors=$validator->validate($genre);
        // if(count($errors)){
        //     $errorsJson=$serializer->serialize($errors,'json');
        //     return new JsonResponse($errorsJson, Response::HTTP_BAD_REQUEST,[],true);
        // }

        $manager->persist($genre);
        $manager->flush();

        return new JsonResponse(
            "le genre a bien ete cree",
            Response::HTTP_CREATED,
            // ["location"=>"api/genres/".$genre->getId()],
            ["location"=> $this->generateUrl('api_genre_affiche', ["id"=>$genre->getId()], UrlGeneratorInterface::ABSOLUTE_URL)],
            true);
    }

    /**
     * @Route("/api/genres/{id}", name="api_genre_edit", methods={"PUT"})
     */
    public function edit_genre(Genre $genre, Request $request, ObjectManager $manager, SerializerInterface $serializer)
    {
        $data=$request->getContent();
        $resultat=$serializer->deserialize($data, Genre::class, 'json',['object_to_populate'=>$genre]);
        $manager->persist($genre);
        $manager->flush();
        
        return new JsonResponse("ca a bien ete modifie", Response::HTTP_OK,[],true);
    }

    /**
     * @Route("/api/genres/{id}", name="api_genre_delete", methods={"DELETE"})
     */
    public function delete_genre(Genre $genre, ObjectManager $manager)
    {
        $manager->persist($genre);
        $manager->flush();
        
        return new JsonResponse("ca a bien ete supprimer", Response::HTTP_OK,[]);
    }
}
