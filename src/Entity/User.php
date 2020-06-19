<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $login;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password_hash;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password_salt;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $auth_token;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserParameter", mappedBy="user")
     */
    private $parameters;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param mixed $login
     *
     * @return User
     */
    public function setLogin($login): User
    {
        $this->login = $login;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPasswordHash()
    {
        return $this->password_hash;
    }

    /**
     * @param mixed $password_hash
     *
     * @return User
     */
    public function setPasswordHash($password_hash): User
    {
        $this->password_hash = $password_hash;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPasswordSalt()
    {
        return $this->password_salt;
    }

    /**
     * @param mixed $password_salt
     *
     * @return User
     */
    public function setPasswordSalt($password_salt): User
    {
        $this->password_salt = $password_salt;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param mixed $parameters
     *
     * @return User
     */
    public function setParameters($parameters): User
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAuthToken()
    {
        return $this->auth_token;
    }

    /**
     * @param mixed $auth_token
     *
     * @return User
     */
    public function setAuthToken($auth_token)
    {
        $this->auth_token = $auth_token;
        return $this;
    }


}
