<?php

namespace App\Doctrine;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\FoodItemEntry;
use App\Entity\Meal;
use App\Entity\MealHistory;
use App\Entity\MealOption;
use App\Entity\MealPlan;
use App\Entity\Recipe;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Security;

class FilterMealPlansByUser implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, ?Operation $operation = null, array $context = []): void
    {
        $this->addWhere($queryBuilder, $resourceClass);
    }

    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, ?Operation $operation = null, array $context = []): void
    {
        $this->addWhere($queryBuilder, $resourceClass);
    }

    private function addWhere(QueryBuilder $queryBuilder, string $resourceClass): void
    {
        $user = $this->security->getUser();
        if (!$user) {
            return;
        }

        if (!$user->getCustomer() or $this->security->isGranted('ROLE_ADMIN')) {
            return;
        }

        if (MealPlan::class === $resourceClass) {
            if ($user->getCustomer()) {
                $rootAlias = $queryBuilder->getRootAliases()[0];
                $queryBuilder->andWhere(sprintf(':customer MEMBER OF %s.customers', $rootAlias));
                $queryBuilder->setParameter('customer', $user->getCustomer());
            }
            if ($user->getProfessional()) {
                $rootAlias = $queryBuilder->getRootAliases()[0];
                $queryBuilder->andWhere(sprintf('%s.professional = :professional', $rootAlias));
                $queryBuilder->setParameter('professional', $user->getProfessional());
            }
        }

        if (Meal::class === $resourceClass) {
            if ($user->getCustomer()) {
                $rootAlias = $queryBuilder->getRootAliases()[0];
                $queryBuilder->leftJoin(sprintf('%s.mealPlans', $rootAlias), 'mp');
                $queryBuilder->andWhere(':customer MEMBER OF mp.customers');
                $queryBuilder->setParameter('customer', $user->getCustomer());
            }
            if ($user->getProfessional()) {
                $rootAlias = $queryBuilder->getRootAliases()[0];
                $queryBuilder->leftJoin(sprintf('%s.mealPlans', $rootAlias), 'mp');
                $queryBuilder->andWhere('mp.professional = :professional');
                $queryBuilder->setParameter('professional', $user->getProfessional());
            }
        }

        if (MealOption::class === $resourceClass) {
            if ($user->getCustomer()) {
                $rootAlias = $queryBuilder->getRootAliases()[0];
                $queryBuilder->leftJoin(sprintf('%s.meals', $rootAlias), 'm');
                $queryBuilder->leftJoin('m.mealPlans', 'mp');
                $queryBuilder->andWhere(':customer MEMBER OF mp.customers');
                $queryBuilder->setParameter('customer', $user->getCustomer());
            }
            if ($user->getProfessional()) {
                $rootAlias = $queryBuilder->getRootAliases()[0];
                $queryBuilder->andWhere(sprintf('%s.professional = :professional', $rootAlias));
                $queryBuilder->setParameter('professional', $user->getProfessional());
            }
        }

        if (FoodItemEntry::class === $resourceClass) {
            if ($user->getCustomer()) {
                $rootAlias = $queryBuilder->getRootAliases()[0];
                $queryBuilder->leftJoin(sprintf('%s.mealOption', $rootAlias), 'mo');
                $queryBuilder->leftJoin('mo.meals', 'm');
                $queryBuilder->leftJoin('m.mealPlans', 'mp');
                $queryBuilder->andWhere(':customer MEMBER OF mp.customers');
                $queryBuilder->setParameter('customer', $user->getCustomer());
            }
        }

        if (Recipe::class === $resourceClass) {
            if ($customer = $user->getCustomer()) {
                $rootAlias = $queryBuilder->getRootAliases()[0];
                $orX = $queryBuilder->expr()->orX();
                $orX->add(sprintf(':customer MEMBER OF %s.customers', $rootAlias));
                if ($professional = $customer->getProfessional()) {
                    $orX->add(sprintf('%s.professional = :professional', $rootAlias));
                }
                $queryBuilder->andWhere($orX);
                $queryBuilder->setParameter('customer', $customer);
                if ($professional = $customer->getProfessional()) {
                    $queryBuilder->setParameter('professional', $professional);
                }
            }
            if ($user->getProfessional()) {
                $rootAlias = $queryBuilder->getRootAliases()[0];
                $queryBuilder->andWhere(sprintf('%s.professional = :professional', $rootAlias));
                $queryBuilder->setParameter('professional', $user->getProfessional());
            }
        }

        if (MealHistory::class === $resourceClass) {
            if ($user->getCustomer()) {
                $rootAlias = $queryBuilder->getRootAliases()[0];
                $queryBuilder->andWhere(sprintf('%s.customer = :customer', $rootAlias));
                $queryBuilder->setParameter('customer', $user->getCustomer());
            }
        }
    }
}