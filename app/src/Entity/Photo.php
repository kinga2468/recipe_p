<?php
/**
 * Photo entity.
 */
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Photo.
 *
 * @ORM\Entity(repositoryClass="App\Repository\PhotoRepository")
 * @ORM\Table(
 *     name="photos",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="UQ_photos_1",
 *              columns={"photo"},
 *          ),
 *     },
 * )
 *
 * @UniqueEntity(
 *     fields={"photo"}
 * )
 */
class Photo
{
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
     * photo.
     *
     * @ORM\Column(
     *     type="string",
     *     length=191,
     *     nullable=false,
     *     unique=true,
     * )
     *
     * @Assert\NotBlank
     * @Assert\Image(
     *     maxSize = "1024k",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg", "image/jpeg", "image/pjpeg"},
     * )
     */
    private $photo;

    /**
     * Recipe.
     *
     * @ORM\OneToOne(
     *     targetEntity="App\Entity\Recipe",
     *     inversedBy="photo"
     * )
     * @ORM\JoinColumn(nullable=false)
     */
    private $recipe;

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
     * Getter for photo.
     *
     * @return mixed|null photo
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Setter for Photo name.
     *
     * @param string $photo Photo
     */
    public function setPhoto($photo): void
    {
        $this->photo = $photo;
    }

    /**
     * Getter for Recipe.
     *
     * @return \App\Entity\Recipe|null Recipe entity
     */
    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    /**
     * Setter for Recipe.
     *
     * @param \App\Entity\Recipe $recipe Recipe entity
     */
    public function setRecipe(Recipe $recipe): void
    {
        $this->recipe = $recipe;
    }
}