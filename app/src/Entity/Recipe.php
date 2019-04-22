<?php
/**
 * Recipe entity.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 *  Recipe class.
 *
 * @ORM\Entity(repositoryClass="App\Repository\RecipeRepository")
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
     * Title.
     *
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=255,
     * )
     */
    private $title;

    /**
     * Created at.
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * Updated at.
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;

    /**
     * Description
     *
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=700
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
     */
    private $photo;

    /**
     *  Time
     *
     * @var string
     *
     * @ORM\Column(
     *     type="integer"
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
     */
    private $people_amount;

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
}
