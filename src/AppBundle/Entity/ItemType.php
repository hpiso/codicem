<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ItemType
 *
 * @ORM\Table(name="item_type")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ItemTypeRepository")
 */
class ItemType
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     *
     * @var int
     *
     * @ORM\Column(name="calorie", type="smallint")
     * @Assert\NotBlank()
     * @Assert\Type(
     *     type="integer",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     * @Assert\LessThan(
     *     value = 500
     * )
     */
    private $calorie;

    /**
     * @Assert\NotBlank()
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255)
     * @Assert\NotBlank()
     *
     * @Assert\Length(
     *      min = 10,
     *      max = 255,
     *      minMessage = "Your url must be at least {{ limit }} characters long",
     *      maxMessage = "Your url cannot be longer than {{ limit }} characters"
     * )
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity="\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id", nullable=false)
     */
    private $createdBy;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return ItemType
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set calorie
     *
     * @param integer $calorie
     * @return ItemType
     */
    public function setCalorie($calorie)
    {
        $this->calorie = $calorie;

        return $this;
    }

    /**
     * Get calorie
     *
     * @return integer 
     */
    public function getCalorie()
    {
        return $this->calorie;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return ItemType
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set createdBy
     *
     * @param \UserBundle\Entity\User $createdBy
     * @return ItemType
     */
    public function setCreatedBy(\UserBundle\Entity\User $createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return \UserBundle\Entity\User 
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }
}
