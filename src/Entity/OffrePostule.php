<?php

namespace App\Entity;

use App\Repository\OffrePostuleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OffrePostuleRepository::class)
 */
class OffrePostule
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_offre;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_user;

    /**
     * @ORM\Column(type="date")
     */
    private $date_postule;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdOffre(): ?int
    {
        return $this->id_offre;
    }

    public function setIdOffre(int $id_offre): self
    {
        $this->id_offre = $id_offre;

        return $this;
    }

    public function getIdUser(): ?int
    {
        return $this->id_user;
    }

    public function setIdUser(int $id_user): self
    {
        $this->id_user = $id_user;

        return $this;
    }

    public function getDatePostule(): ?\DateTimeInterface
    {
        return $this->date_postule;
    }

    public function setDatePostule(\DateTimeInterface $date_postule): self
    {
        $this->date_postule = $date_postule;

        return $this;
    }
}
