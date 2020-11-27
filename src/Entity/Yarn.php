<?php

namespace App\Entity;

use App\Repository\YarnRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass=YarnRepository::class)
 * @UniqueEntity("name")
 */
class Yarn
{
    const YARN_WEIGHT = [
        'Superfine' => 'Superfine',
        'Fine' => 'Fine',
        'Légère' => 'Légère',
        'Moyenne' => 'Moyenne',
        'Epaisse' => 'Epaisse',
        'Très épaisse' => 'Très épaisse',
        'Géante' => 'Géante'
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez renseigner un nom")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez sélectionner une catégorie de fil.")
     */
    private $weight;

    /**
     * @ORM\ManyToMany(targetEntity=Pattern::class, inversedBy="yarns")
     */
    private $patterns;

    /**
     * @ORM\ManyToOne(targetEntity=Brand::class, inversedBy="yarns")
     * @ORM\JoinColumn(nullable=false)
     */
    private $brand;

    public function __construct()
    {
        $this->patterns = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getWeight(): ?string
    {
        return $this->weight;
    }

    public function setWeight(string $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * @return Collection|Pattern[]
     */
    public function getPatterns(): Collection
    {
        return $this->patterns;
    }

    public function addPattern(Pattern $pattern): self
    {
        if (!$this->patterns->contains($pattern)) {
            $this->patterns[] = $pattern;
        }

        return $this;
    }

    public function removePattern(Pattern $pattern): self
    {
        $this->patterns->removeElement($pattern);

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }
}
