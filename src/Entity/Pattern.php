<?php

namespace App\Entity;

use App\Repository\PatternRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use PhpParser\Node\Scalar\String_;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=PatternRepository::class)
 * @UniqueEntity("name")
 */
class Pattern
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
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="Le nom ne peut contenir un chiffre.",
     *     htmlPattern = false
     * )
     * @Assert\Length(
     *      max = 20,
     *      maxMessage = "Le nom doit contenir au maximum {{ limit }} charactères",
     *      allowEmptyString = false
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Veuillez renseigner une description")
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Veuillez renseigner le niveau de difficulté")
     * @Assert\Range(
     *     min = 1,
     *     max = 5,
     *     notInRangeMessage = "Le niveau de difficulté doit êtr compris entre {{ min }} et {{ max }}.",
     * )
     */
    private $difficulty;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="patterns")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Veuillez sélectionner une catégorie.")
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity=Yarn::class, inversedBy="patterns")
     * @Assert\NotBlank(message="Veuillez sélectionner un ou plusieurs fils.")
     */
    private $yarns;

    /**
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="pattern", orphanRemoval=true, cascade={"persist"})
     */
    private $images;

    /**
     * @ORM\Column(type="string")
     */
    private $pdfFilename;

    public function __construct()
    {
        $this->yarns = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->images = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDifficulty(): ?int
    {
        return $this->difficulty;
    }

    public function setDifficulty(int $difficulty): self
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getPdfFilename(): ?String
    {
        return $this->pdfFilename;
    }

    public function setPdfFilename(?String $pdfFilename)
    {
        $this->pdfFilename = $pdfFilename;

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
            $yarn->addPattern($this);
        }

        return $this;
    }

    public function removeYarn(Yarn $yarn): self
    {
        if ($this->yarns->removeElement($yarn)) {
            $yarn->removePattern($this);
        }

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setPattern($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getPattern() === $this) {
                $image->setPattern(null);
            }
        }

        return $this;
    }
}
