<?php

namespace Topikito\Acme\Provider;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\DBAL\connection;

class User implements UserProviderInterface
{
    private $_db;

    public function __construct(connection $db)
    {

    }

    public function loadUserByUsername($username)
    {

    }

    public function buildUser($user)
    {

    }

    public function refreshUser(UserInterface $user)
    {

    }

    public function supportsClass($class)
    {

    }
}
