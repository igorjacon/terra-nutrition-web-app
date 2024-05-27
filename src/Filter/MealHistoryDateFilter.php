<?php

namespace App\Filter;

use ApiPlatform\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\PropertyInfo\Type;

class MealHistoryDateFilter extends AbstractFilter
{
    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder,
                                      QueryNameGeneratorInterface $queryNameGenerator,
                                      string $resourceClass,
                                      ?Operation $operation = null,
                                      array $context = []): void
    {
        $rootAlias = $queryBuilder->getRootAliases()[0];
        // Otherwise filter is applied to order and page as well
        if (
            !$this->isPropertyEnabled($property, $resourceClass) ||
            !$this->isPropertyMapped($property, $resourceClass)
        ) {
            return;
        }

        $parameterName = $queryNameGenerator->generateParameterName($property);
        $value = (new \DateTime($value))->format('Y-m-d');
        $queryBuilder
            ->andWhere(sprintf('%s.%s = :%s', $rootAlias, $property, $parameterName))
            ->setParameter($parameterName, $value);
    }

    public function getDescription(string $resourceClass): array
    {
        if (!$this->properties) {
            return [];
        }

        $description = [];
        foreach ($this->properties as $property => $strategy) {
            $description["$property"] = [
                'property' => $property,
                'type' => Type::BUILTIN_TYPE_STRING,
                'required' => false,
                'description' => 'Filter meal history by date.',
                'openapi' => [
                    'example' => '2024-05-26T11:14:00',
                    'allowReserved' => false,// if true, query parameters will be not percent-encoded
                    'allowEmptyValue' => true,
                    'explode' => false, // to be true, the type must be Type::BUILTIN_TYPE_ARRAY, ?product=blue,green will be ?product=blue&product=green
                ],
            ];
        }

        return $description;
    }
}