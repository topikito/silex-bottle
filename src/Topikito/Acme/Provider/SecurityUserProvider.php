<?php

namespace Topikito\Acme\Provider;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\DBAL\connection;

/**
 * Class SecurityUserProvider
 *
 * @package Topikito\Acme\Provider
 */
class SecurityUserProvider implements UserProviderInterface
{
    /**
     * @var connection
     */
    private $_db;

    /**
     * @param connection $db
     */
    public function __construct(connection $db)
    {
        $this->_db = $db;
    }

    /**
     * @param string $username
     *
     * @return User
     * @throws \Doctrine\DBAL\DBALException
     */
    public function loadUserByUsername($username)
    {
        $stmt = $this->_db->executeQuery('SELECT * FROM user WHERE email = ?', array(strtolower($username)));
        if (!$user = $stmt->fetch()) {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
        }

        // temp until we implement roles
        $user['roles'] = 'ROLE_VISITOR';

        return new User($user['email'], $user['password']);
    }

    /**
     * @param string $id
     *
     * @return User
     * @throws \Doctrine\DBAL\DBALException
     */
    public function loadUserById($id)
    {
        $stmt = $this->_db->executeQuery('SELECT * FROM user WHERE id = ?', array(strtolower($id)));
        if (!$user = $stmt->fetch()) {
            throw new UsernameNotFoundException(sprintf('User "%d" does not exist.', $id));
        }

        // temp until we implement roles
        $user['roles'] = 'ROLE_VISITOR';

        return new User($user['email'], $user['password']);
    }

    /**
     * @param UserInterface $user
     *
     * @return User
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return $class === '\Symfony\Component\Security\Core\User\User';
    }
}
