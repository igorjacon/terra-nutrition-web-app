<?php

namespace App\Utils;

use App\Entity\User;
use Symfony\Component\Console\Exception\InvalidArgumentException;

class Validator
{
    /**
     * @param null|string $password
     *
     * @return string
     */
    public static function validatePassword(?string $password): string
    {
        if (empty($password)) {
            throw new InvalidArgumentException('The password can not be empty.');
        }

        if (!preg_match('/^S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/', $password)) {
            throw new InvalidArgumentException(
                'The password have must be 8 characters, contains at least 1 number, 1 uppercase and lowercase character'
            );
        }

        return $password;
    }

    /**
     * @param null|string $email
     *
     * @return string
     */
    public static function validateEmail(?string $email): string
    {
        if (empty($email)) {
            throw new InvalidArgumentException('The email can not be empty.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('The email should look like a real email.');
        }

        return $email;
    }

    /**
     * @param null|string $fullName
     *
     * @return string
     */
    public static function validateFullName(?string $fullName): string
    {
        if (empty($fullName)) {
            throw new InvalidArgumentException('The full name can not be empty.');
        }

        return $fullName;
    }

    /**
     * @param null|string $roles
     *
     * @return string
     */
    public static function validateRoles(?string $roles): string
    {
        if (empty($roles)) {
            throw new InvalidArgumentException('The role can not be empty.');
        }

        $roles = explode(',', $roles);

        $invalidRoles = array_diff($roles, User::ROLES_ALLOWED);
        if (count($invalidRoles) > 0) {
            throw new InvalidArgumentException('The role(s) : ' . implode(',', $invalidRoles) . ' does not exist in the list of roles allowed : ' . implode(',', User::ROLES_ALLOWED));
        }

        return implode(',', $roles);
    }
}