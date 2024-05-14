<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Professional;
use App\Repository\ProfessionalRepository;

class ProfessionalProvider implements ProviderInterface
{
    private ProfessionalRepository $professionalRepository;

    public function __construct(ProfessionalRepository $professionalRepository)
    {
        $this->professionalRepository = $professionalRepository;
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): Professional|array|null
    {
        return $this->professionalRepository->findOneBy(["user" => $uriVariables['id']]);
    }
}
