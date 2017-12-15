<?php
namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WebserviceUserRepository")
 */
class WebserviceUser implements UserInterface, EquatableInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */    
    private $username;

    /**
     * @ORM\Column(type="string", length=100)
     */    
    private $password;
    
    /**
     * @ORM\Column(type="string", length=100)
     */    
    private $salt;
    
    /**
     * @ORM\Column(type="array", length=100)
     */    
    private $roles;
    
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $apiKey;

    public function __construct($username, $password, $salt, array $roles, $apiKey)
    {
        $this->username = $username;
        $this->password = $password;
        $this->salt = $salt;
        $this->roles = $roles;
        $this->apiKey = $apiKey;
    }

    public function getId() {
        return $this->id;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }

    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }

    public function eraseCredentials()
    {
    }

    public function isEqualTo(UserInterface $user)
    {
        if (!$user instanceof WebserviceUser) {
            return false;
        }

        if ($this->password !== $user->getPassword()) {
            return false;
        }

        if ($this->salt !== $user->getSalt()) {
            return false;
        }

        if ($this->username !== $user->getUsername()) {
            return false;
        }
        
        if ($this->apiKey !== $user->getApiKey()) {
            return false;
        }

        return true;
    }
}