<?php

namespace App\Filter;

use App\Attribute\UserAware;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

final class CustomerFilter extends SQLFilter
{

    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        // The Doctrine filter is called for any query on any entity
        // Check if the current entity is "user aware" (marked with an attribute)
        $userAware = $targetEntity->getReflectionClass()->getAttributes(UserAware::class)[0] ?? null;

//        dump($targetEntity);
//        dump($userAware);
        $fieldName = $userAware?->getArguments()['userFieldName'] ?? null;
        if ($fieldName === '' || is_null($fieldName)) {
            return '';
        }

        try {
            // Don't worry, getParameter automatically escapes parameters
            $customerId = $this->getParameter('customer_id');
        } catch (\InvalidArgumentException $e) {
            // No user ID has been defined
            return '';
        }

        if (empty($fieldName) || empty($customerId)) {
            return '';
        }

        return sprintf('%s.%s = %s', $targetTableAlias, $fieldName, $customerId);
    }
}