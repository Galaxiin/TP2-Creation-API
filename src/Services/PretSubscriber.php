<?php

namespace App\Services;

use App\Entity\Pret;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PretSubscriber implements EventSubscriberInterface
{
    private $token;

    public function __construct(TokenStorageInterface $token)
    {
        $this->token = $token;
    }

    public static function getSubscribedEvents()
    {
        return[
            KernelEvents::VIEW => ['getAuthenticatedUser', EventPriorities::PRE_WRITE]
        ];
    }

    public function getAuthenticatedUser(GetResponseForControllerResultEvent $event){
        $entity = $event->getControllerResult(); // Récupère l'entité qui a déclencher l'event
        $method = $event->getRequest()->getMethod(); // Récupère la méthode invoquée dans la requete
        $adherent = $this->token->getToken()->getUser(); // Récupère l'adhérent actuellement connecté
        if ($entity instanceof Pret) {// Si c'est bien une entité Pret
            if ($method == Request::METHOD_POST) { // Si c'est bien une requete de type POST
                $entity->setAdherent($adherent); // On écrit l'adhérent dans la propriété adhérent de l'entite Pret
            }elseif ($method == Request::METHOD_PUT) {
                if ($entity->getDateRetourReelle() == null) {
                    $entity->getLivre()->setDispo(false);
                }else {
                    $entity->getLivre()->setDispo(true);
                }
            }elseif ($method == Request::METHOD_DELETE) {
                $entity->getLivre()->setDispo(true);
            }
        }
        return;
    }
}