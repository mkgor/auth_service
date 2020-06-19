<?php

namespace App\Entity;

use App\Repository\UserParameterRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass=UserParameterRepository::class)
 */
class UserParameter
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="parameters", fetch="EXTRA_LAZY")
     * @Serializer\Exclude()
     */
    private $user;

    /**
     * @ORM\Column(type="string")
     */
    private $param_key;

    /**
     * @ORM\Column(type="text")
     */
    private $param_value;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     *
     * @return UserParameter
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getParamKey()
    {
        return $this->param_key;
    }

    /**
     * @param mixed $param_key
     *
     * @return UserParameter
     */
    public function setParamKey($param_key)
    {
        $this->param_key = $param_key;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getParamValue()
    {
        return $this->param_value;
    }

    /**
     * @param mixed $param_value
     *
     * @return UserParameter
     */
    public function setParamValue($param_value)
    {
        $this->param_value = $param_value;
        return $this;
    }

}
