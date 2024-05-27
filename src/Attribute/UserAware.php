<?php

namespace App\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class UserAware
{
    public $userFieldName;

    public function __construct($userFieldName)
    {
        $this->userFieldName = $userFieldName;
    }
}