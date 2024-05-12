<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Customer;
use App\Repository\CustomerRepository;

class CustomerProvider implements ProviderInterface
{
    private CustomerRepository $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): Customer|array|null
    {
        return $this->customerRepository->findOneBy(["user" => $uriVariables['id']]);
    }
}
