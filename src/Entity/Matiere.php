<?php

namespace App\Entity;

use App\Repository\MatiereRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MatiereRepository::class)]
class Matiere
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Chapeau>
     */
    #[ORM\OneToMany(targetEntity: Chapeau::class, mappedBy: 'matiere', orphanRemoval: true)]
    private Collection $chapeaux;

    public function __construct()
    {
        $this->chapeaux = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Chapeau>
     */
    public function getChapeaux(): Collection
    {
        return $this->chapeaux;
    }

    public function addChapeaux(Chapeau $chapeaux): static
    {
        if (!$this->chapeaux->contains($chapeaux)) {
            $this->chapeaux->add($chapeaux);
            $chapeaux->setMatiere($this);
        }

        return $this;
    }

    public function removeChapeaux(Chapeau $chapeaux): static
    {
        if ($this->chapeaux->removeElement($chapeaux)) {
            // set the owning side to null (unless already changed)
            if ($chapeaux->getMatiere() === $this) {
                $chapeaux->setMatiere(null);
            }
        }

        return $this;
    }
}
