<?php

namespace App\Controller\Api;

use App\Entity\Auteur;
use App\Repository\AuteurRepository;
use App\Repository\NationaliteRepository;
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

class AuteurController extends AbstractController
{
    /**
     * @Route("/api/auteurs", name="api_auteurs", methods="GET")
     */
    public function liste_auteurs(SerializerInterface $serializer, AuteurRepository $repo)
    {
        $auteurs=$repo->findAll();
        $resultat=$serializer->serialize($auteurs, 'json',[
            'groups' => ['ListeComplexeAuteur']
        ]);
        
        return new JsonResponse($resultat,200,[],true);
    }

    /**
     * @Route("/api/auteurs/{id}", name="api_auteur_affiche", methods={"GET"})
     */
    public function affiche_auteur(Auteur $auteur, SerializerInterface $serializer)
    {
        $resultat=$serializer->serialize($auteur, 'json',[
            'groups' => ['ListeSimpleAuteur']
        ]);
        
        return new JsonResponse($resultat,Response::HTTP_OK,[],true);
    }

    /**
     * @Route("/api/auteurs", name="api_auteur_create", methods="POST")
     */
    public function create_auteur(Request $request, NationaliteRepository $repoNatio, ObjectManager $manager, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $data=$request->getContent();
        $dataTab=$serializer->decode($data,'json');
        $auteur=new Auteur();
        $nationalite=$repoNatio->find($dataTab["nationalite"]["id"]);

        $serializer->deserialize($data, Auteur::class, 'json',['object_to_populate'=>$auteur]);
        $auteur->setNationalite($nationalite);

        //gestion des erreurs de validations    ajouter les assert necessaire !

        // $errors=$validator->validate($auteur);
        // if(count($errors)){
        //     $errorsJson=$serializer->serialize($errors,'json');
        //     return new JsonResponse($errorsJson, Response::HTTP_BAD_REQUEST,[],true);
        // }

        $manager->persist($auteur);
        $manager->flush();

        return new JsonResponse(
            "l'auteur a bien ete cree",
            Response::HTTP_CREATED,
            // ["location"=>"api/auteurs/".$auteur->getId()],
            ["location"=> $this->generateUrl('api_auteur_affiche', ["id"=>$auteur->getId()], UrlGeneratorInterface::ABSOLUTE_URL)],
            true);
    }

    /**
     * @Route("/api/auteurs/{id}", name="api_auteur_edit", methods={"PUT"})
     */
    public function edit_auteur(Auteur $auteur, NationaliteRepository $repoNatio, Request $request, ObjectManager $manager, SerializerInterface $serializer)
    {
        $data=$request->getContent();
        $dataTab=$serializer->decode($data,'json');
        $nationalite=$repoNatio->find($dataTab["nationalite"]["id"]);

        //solution 1
        $serializer->deserialize($data, Auteur::class, 'json',['object_to_populate'=>$auteur]);
        $auteur->setNationalite($nationalite);

        //solution 2
        //$serializer->denormalize($dataTab['auteur'],Auteur::class,null,['object_to_populate'=>$auteur]);

        //gestion des erreurs de validations    ajouter les assert necessaire !

        // $errors=$validator->validate($auteur);
        // if(count($errors)){
        //     $errorsJson=$serializer->serialize($errors,'json');
        //     return new JsonResponse($errorsJson, Response::HTTP_BAD_REQUEST,[],true);
        // }

        $manager->persist($auteur);
        $manager->flush();
        
        return new JsonResponse("Ca a bien ete modifie", Response::HTTP_OK,[],true);
    }

    /**
     * @Route("/api/auteurs/{id}", name="api_auteur_delete", methods={"DELETE"})
     */
    public function delete_auteur(Auteur $auteur, ObjectManager $manager)
    {
        $manager->persist($auteur);
        $manager->flush();
        
        return new JsonResponse("Ca a bien ete supprimer", Response::HTTP_OK,[]);
    }
}
