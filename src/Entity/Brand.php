<?php

namespace App\Entity;

use App\Repository\BrandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass=BrandRepository::class)
 * @UniqueEntity("name")
 */
class Brand
{
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
     * @ORM\OneToMany(targetEntity=Yarn::class, mappedBy="brand", orphanRemoval=true)
     * @Assert\NotBlank(message="Veuillez sélectionner un nom de fil.")
     */
    private $yarns;

    public function __construct()
    {
        $this->yarns = new ArrayCollection();
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

    /**
     * @return Collection|Yarn[]
     */
    public function getYarns(): Collection
    {
        return $this->yarns;
    }

    public function addYarn(Yarn $yarn): self
    {
        if (!$this->yarns->contains($yarn)) {
            $this->yarns[] = $yarn;
            $yarn->setBrand($this);
        }

        return $this;
    }

    public function removeYarn(Yarn $yarn): self
    {
        if ($this->yarns->removeElement($yarn)) {
            // set the owning side to null (unless already changed)
            if ($yarn->getBrand() === $this) {
                $yarn->setBrand(null);
            }
        }

        return $this;
    }
}
