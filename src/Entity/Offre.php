<?php

namespace App\Entity;

use App\Repository\OffreRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Type;

/**
 * @ORM\Entity(repositoryClass=OffreRepository::class)
 */
class Offre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $titre;

    /**
     * @ORM\Column(type="date")
     */
    private $date_publication;

    /**
     * @ORM\Column(type="date")
     */
    private $date_fin;

    /**
     * @ORM\Column(type="text")
     */
    private $detail;

    /**
     * @ORM\Column(type="integer")
     * @ORM\ManyToOne(targetEntity="App\Entity\Type", inversedBy="offres")
     * @ORM\JoinColumn(name="type", referencedColumnName="id")
     */
    private $type;

    /**
     * @ORM\Column(type="bigint")
     */
    private $salaire_min;

    /**
     * @ORM\Column(type="bigint")
     */
    private $salaire_max;

    /**
     * @ORM\Column(type="integer")
     */
    private $temps;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_recruteur;

    /**
     * @ORM\Column(type="integer")
     */
    private $done;

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDatePublication(): ?\DateTimeInterface
    {
        return $this->date_publication;
    }

    public function setDatePublication(\DateTimeInterface $date_publication): self
    {
        $this->date_publication = $date_publication;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeInterface $date_fin): self
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    public function getDetail(): ?string
    {
        return $this->detail;
    }

    public function setDetail(string $detail): self
    {
        $this->detail = $detail;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getSalaireMin(): ?string
    {
        return $this->salaire_min;
    }

    public function setSalaireMin(string $salaire_min): self
    {
        $this->salaire_min = $salaire_min;

        return $this;
    }

    public function getSalaireMax(): ?string
    {
        return $this->salaire_max;
    }

    public function setSalaireMax(string $salaire_max): self
    {
        $this->salaire_max = $salaire_max;

        return $this;
    }

    public function getTemps(): ?int
    {
        return $this->temps;
    }

    public function setTemps(int $temps): self
    {
        $this->temps = $temps;

        return $this;
    }

    public function getIdRecruteur(): ?int
    {
        return $this->id_recruteur;
    }

    public function setIdRecruteur(int $id_recruteur): self
    {
        $this->id_recruteur = $id_recruteur;

        return $this;
    }

    public function getDone(): ?int
    {
        return $this->done;
    }

    public function setDone(int $done): self
    {
        $this->done = $done;

        return $this;
    }
}
