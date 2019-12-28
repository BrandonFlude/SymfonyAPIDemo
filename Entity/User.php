<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
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
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email_address;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $lucky_number;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getEmailAddress(): ?string
    {
        return $this->email_address;
    }

    public function setEmailAddress(?string $email_address): self
    {
        $this->email_address = $email_address;

        return $this;
    }

    public function getLuckyNumber(): ?int
    {
        return $this->lucky_number;
    }

    public function setLuckyNumber(?int $lucky_number): self
    {
        $this->lucky_number = $lucky_number;

        return $this;
    }
}
