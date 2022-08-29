<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use App\Repository\CheeseListingRepository;
use Carbon\Carbon;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

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
#[ApiFilter(SearchFilter::class, properties: ['title' => 'partial', 'description' => 'partial'])]
#[ApiFilter(BooleanFilter::class, properties: ['isPublished'])]
#[ApiFilter(RangeFilter::class, properties: ['price'])]
#[ApiFilter(PropertyFilter::class)]
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
    #[Groups(['cheese:read'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['cheese:read', 'cheese:write'])]
    private ?int $price = null;

    #[ORM\Column]
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

    #[Groups(['cheese:write'])]
    #[SerializedName('description')]
    public function setTextDescription(string $description): self
    {
        $this->description = nl2br($description);

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

    #[Groups(['cheese:read'])]
    public function getCreatedAtAgo(): ?string
    {
        return Carbon::instance($this->createdAt)->diffForHumans();
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
