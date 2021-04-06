<?php

namespace App\Serializer;

use ApiPlatform\Core\Serializer\SerializerContextBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use App\Entity\Livre;

final class Livre_Context_Builder implements SerializerContextBuilderInterface
{
    private $decorated;
    private $authorizationChecker;

    public function __construct(SerializerContextBuilderInterface $decorated, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->decorated = $decorated;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function createFromRequest(Request $request, bool $normalization, ?array $extractedAttributes = null): array
    {
        $context = $this->decorated->createFromRequest($request, $normalization, $extractedAttributes);
        $resourceClass = $context['resource_class'] ?? null;

        if ($resourceClass === Livre::class && isset($context['groups']) 
            && $this->authorizationChecker->isGranted('ROLE_MANAGER') && $normalization === true) {
            $context['groups'][] = 'role_manager';
        }
        if ($resourceClass === Livre::class && isset($context['groups']) 
            && $this->authorizationChecker->isGranted('ROLE_ADMIN') && $normalization === false) {
                // CONDITION DE LA METHODE
                // if ($request->getMethod() == "PUT") {
                //     # code...
                // }
            $context['groups'][] = 'role_admin';
        }

        return $context;
    }
}