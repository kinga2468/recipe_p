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
    const NUMBER_OF_ITEMS = 5;

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
     *  Photo
     *
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=128
     * )
     * @Assert\Length(
     *     min="3",
     *     max="128",
     * )
     */
    private $photo;

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
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
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
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
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
    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
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
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Getter for Photo.
     *
     * @return string|null Photo
     */
    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    /**
     * Setter for Photo.
     *
     * @param string $photo Photo
     */
    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
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
    public function setTime(int $time): self
    {
        $this->time = $time;

        return $this;
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
    public function setPeopleAmount(int $people_amount): self
    {
        $this->people_amount = $people_amount;

        return $this;
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
}
