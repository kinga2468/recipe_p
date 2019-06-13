<?php
/**
 * Recipe entity.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *  Recipe class.
 *
 * @ORM\Entity(repositoryClass="App\Repository\RecipeRepository")
 *
 * @UniqueEntity(fields={"title"})
 */
class Recipe
{
    /**
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See http://symfony.com/doc/current/best_practices/configuration.html#constants-vs-configuration-options
     *
     * @constant int NUMBER_OF_ITEMS
     */
    const NUMBER_OF_ITEMS = 9;
    /**
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See http://symfony.com/doc/current/best_practices/configuration.html#constants-vs-configuration-options
     *
     * @constant int NUMBER_OF_ITEMS_MATCHING
     */
    const NUMBER_OF_ITEMS_MATCHING = 3;
    /**
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See http://symfony.com/doc/current/best_practices/configuration.html#constants-vs-configuration-options
     *
     * @constant int NUMBER_OF_ITEMS
     */
    const NUMBER_OF_RECIPES = 2;

    /**
     * Primary key.
     *
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Created at.
     *
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime
     */
    private $createdAt;

    /**
     * Updated at.
     *
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime
     */
    private $updatedAt;

    /**
     * Title.
     *
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=255
     * )
     *
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="255",
     * )
     */
    private $title;

    /**
     * Description
     *
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=700
     * )
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="10",
     *     max="700",
     * )
     */
    private $description;

    /**
     *  Time
     *
     * @var string
     * @Assert\NotBlank
     * @ORM\Column(
     *     type="integer"
     * )
     * @Assert\Range(
     *      min = 1,
     *      max = 240,
     *      minMessage = "Time must be more than {{ limit }} min",
     *      maxMessage = "Time must be less than {{ limit }} min"
     * )
     */
    private $time;

    /**
     *  People amount
     *
     * @var string
     *
     * @ORM\Column(
     *     type="integer"
     * )
     * @Assert\NotBlank
     *
     * @Assert\Range(
     *      min = 1,
     *      max = 6,
     *      minMessage = "People amount must be more than {{ limit }}",
     *      maxMessage = "People amount be less than {{ limit }}"
     * )
     */
    private $people_amount;

    /**
     * Code.
     *
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=255
     * )
     *
     * @Gedmo\Slug(fields={"title"})
     *
     * @Assert\Length(
     *     min="3",
     *     max="255",
     * )
     */
    private $code;

    /**
     * Tags.
     * @var array
     *
     * @ORM\ManyToMany(
     *     targetEntity="App\Entity\Tag",
     *     inversedBy="recipes",
     *     orphanRemoval=true,
     *     )
     *
     * @ORM\JoinTable(name="recipes_tags")
     */
    private $tags;


    /**
     * Comments
     * @var array
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="recipe", orphanRemoval=true)
     * @ORM\JoinTable(name="recipes_comments")
     */
    private $comments;

    /**
     * Author.
     *
     * @var \App\Entity\User
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\User",
     *     inversedBy="recipes",
     * )
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * Tags.
     *
     * @var array
     *
     * @ORM\ManyToMany(
     *     targetEntity="App\Entity\Ingredient", 
     *     inversedBy="recipes",
     *     orphanRemoval=true,
     *     cascade={"persist"}
     * )
     * @ORM\JoinTable(name="recipes_ingredients")
     */
    private $ingredients;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Photo", mappedBy="recipe", cascade={"persist", "remove"})
     */
    private $photo;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->ingredients = new ArrayCollection();
    }

    /**
     * Getter for Id.
     *
     * @return int|null Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for Title.
     *
     * @return string|null Title
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Setter for Title.
     *
     * @param string $title Title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Getter for Created at.
     *
     * @return \DateTimeInterface|null Created at
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Setter for Created at.
     *
     * @param \DateTimeInterface $createdAt Created at
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Getter for Updated at.
     *
     * @return \DateTimeInterface|null Updated at
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * Setter for Updated at.
     *
     * @param \DateTimeInterface $updatedAt Updated at
     */
    public function setUpdatedAt(\DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Getter for Description.
     *
     * @return string|null Description
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Setter for Description.
     *
     * @param string $description Description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * Getter for Time.
     *
     * @return integer|null Time
     */
    public function getTime(): ?int
    {
        return $this->time;
    }

    /**
     * Setter for Time.
     *
     * @param integer $time Time
     */
    public function setTime(int $time): void
    {
        $this->time = $time;
    }

    /**
     * Getter for PeopleAmount.
     *
     * @return integer|null PeopleAmount
     */
    public function getPeopleAmount(): ?int
    {
        return $this->people_amount;
    }

    /**
     * Setter for PeopleAmount.
     *
     * @param integer $peopleAmount PeopleAmount
     */
    public function setPeopleAmount(int $people_amount): void
    {
        $this->people_amount = $people_amount;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): void
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }
    }

    public function removeTag(Tag $tag): void
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): void
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setRecipe($this);
        }
    }

    public function removeComment(Comment $comment): void
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getRecipe() === $this) {
                $comment->setRecipe(null);
            }
        }
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): void
    {
        $this->author = $author;
    }

    /**
     * @return Collection|Ingredient[]
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredient $ingredient): void
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients[] = $ingredient;
        }

        $ingredient->addRecipe($this);
        $this->ingredients->add($ingredient);
    }

    public function removeIngredient(Ingredient $ingredient): void
    {
        if ($this->ingredients->contains($ingredient)) {
            $this->ingredients->removeElement($ingredient);
        }
    }

    public function getPhoto(): ?Photo
    {
        return $this->photo;
    }

    public function setPhoto($photo): self
    {
        $this->photo = $photo;

        // set the owning side of the relation if necessary
//        if ($this !== $photo->getRecipe()) {
//            $photo->setRecipe($this);
//        }

        return $this;
    }


}
