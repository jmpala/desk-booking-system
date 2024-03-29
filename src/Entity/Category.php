<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['bookable:read'])]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    #[Groups(['bookable:read'])]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Bookable::class)]
    private Collection $bookables;

    public function __construct()
    {
        $this->bookables = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
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
     * @return Collection<int, Bookable>
     */
    public function getBookables(): Collection
    {
        return $this->bookables;
    }

    public function addBookable(Bookable $bookable): self
    {
        if (!$this->bookables->contains($bookable)) {
            $this->bookables->add($bookable);
            $bookable->setCategory($this);
        }

        return $this;
    }

    public function removeBookable(Bookable $bookable): self
    {
        if ($this->bookables->removeElement($bookable)) {
            // set the owning side to null (unless already changed)
            if ($bookable->getCategory() === $this) {
                $bookable->setCategory(null);
            }
        }

        return $this;
    }
}
