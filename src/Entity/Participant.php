<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ParticipantRepository::class)
 */
class Participant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_subscribe;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fonction;

    /**
     * @ORM\ManyToOne(targetEntity=Formation::class, inversedBy="participants")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Formation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function isIsSubscribe(): ?bool
    {
        return $this->is_subscribe;
    }

    public function setIsSubscribe(bool $is_subscribe): self
    {
        $this->is_subscribe = $is_subscribe;

        return $this;
    }

    public function getFonction(): ?string
    {
        return $this->fonction;
    }

    public function setFonction(string $fonction): self
    {
        $this->fonction = $fonction;

        return $this;
    }

    public function getFormation(): ?Formation
    {
        return $this->Formation;
    }

    public function setFormation(?Formation $Formation): self
    {
        $this->Formation = $Formation;

        return $this;
    }
}
