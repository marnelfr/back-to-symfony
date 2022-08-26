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
    itemOperations: [
        'GET' => ['path' => '/lov/de/{id}'],
        'PUT', 'PATCH', 'DELETE'],
    shortName: 'Cheese',
    denormalizationContext: ['groups' => ['write:cheese'], 'swagger_definition_name' => 'Write'],
    formats: ['jsonld', 'html', 'json', 'csv' => ['text/csv']],
    normalizationContext: ['groups' => ['read:cheese'], 'swagger_definition_name' => 'Read'],
    paginationItemsPerPage: 2,
    paginationPartial: true
)]
#[ApiFilter(BooleanFilter::class, properties: ['isPublished'])]
#[ApiFilter(SearchFilter::class, properties: ['title' => 'partial', 'description' => 'partial'])]
#[ApiFilter(RangeFilter::class, properties: ['price'])]
#[ApiFilter(PropertyFilter::class)]
class CheeseListing
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:cheese'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read:cheese', 'write:cheese'])]
    private ?string $title = null;

    /**
     * The price of our lovely cheese
     *
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['write:cheese', 'read:cheese'])]
    #[SerializedName('textDescription')]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['read:cheese', 'write:cheese'])]
    private ?int $price = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Groups(['read:cheese'])]
    private ?bool $isPublished = false;


    public function __construct(string $title = null)
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->title = $title;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

//    public function setTitle(string $title): self
//    {
//        $this->title = $title;
//
//        return $this;
//    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    #[Groups('write:cheese')]
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    #[Groups('write:cheese')]
    /**
     * The description of the text with line break
     */
    #[SerializedName("description")]
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

    #[Groups(['read:cheese'])]
    /**
     * How long ago in text that this cheese listing was added.
     */
    public function getCreatedAtAgo(): string
    {
        return Carbon::instance($this->getCreatedAt())->diffForHumans();
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
