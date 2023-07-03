<?php

namespace App\Entity;

use App\Repository\JobbeursRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=JobbeursRepository::class)
 */
class Jobbeurs
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $competences;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="jobbeurs")
     */
    private $chercheur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompetences(): ?string
    {
        return $this->competences;
    }

    public function setCompetences(?string $competences): self
    {
        $this->competences = $competences;

        return $this;
    }

    public function getChercheur(): ?User
    {
        return $this->chercheur;
    }

    public function setChercheur(?User $chercheur): self
    {
        $this->chercheur = $chercheur;

        return $this;
    }
}
