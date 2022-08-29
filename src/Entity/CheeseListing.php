<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CheeseListingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CheeseListingRepository::class)]
#[ApiResource(
    collectionOperations: ['GET', 'POST'],
    itemOperations: ['GET', 'PUT', 'PATCH', 'DELETE'],
    shortName: 'Cheese',
    denormalizationContext: [
        'groups' => ['cheese:write'],
        'swagger_definition_name' => 'Write'
    ],
    normalizationContext: [
        'groups' => ['cheese:read'],
        'swagger_definition_name' => 'Read'
    ]
)]
class CheeseListing
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['cheese:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['cheese:read', 'cheese:write'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['cheese:read', 'cheese:write'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['cheese:read', 'cheese:write'])]
    private ?int $price = null;

    #[ORM\Column]
    #[Groups(['cheese:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(options: ['default' => false])]
    #[Groups(['cheese:read'])]
    private ?bool $isPublished = null;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTimeImmutable());
        $this->setIsPublished(false);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function isIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }
}
