<?php
namespace App\Security;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use App\Entity\WebserviceUser;

class ApiKeyUserProvider implements UserProviderInterface
{
    public function __construct($em) {
        $this->em = $em;
    }

    public function getUsernameForApiKey($apiKey)
    {
        $user = $this->em->getRepository(WebserviceUser::class)->findOneBy([
            'apiKey' => $apiKey
        ]); 
        if($user != null ){
            $username = $user->getUsername();
            return $username;
        } else {
            return false;
        }
    }

    public function loadUserByUsername($username)
    {
        $repository = $this->em->getRepository(WebserviceUser::class);
        $user = $repository->findOneBy([
            'username' => $username
        ]);
        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        // if (!$user instanceof WebserviceUser) {
        //     throw new UnsupportedUserException(
        //         sprintf('Instances of "%s" are not supported.', get_class($user))
        //     );
        // }

        // return $this->loadUserByUsername($user->getUsername());
        return $user;
    }

    public function supportsClass($class)
    {
        return WebserviceUser::class === $class;
    }
}