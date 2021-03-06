<?php

namespace Ipeer\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Symfony\Component\Validator\Constraints as Assert;
use Ipeer\CourseBundle\Entity\Enrollment;
use Doctrine\Common\Collections\ArrayCollection;
use Ipeer\CourseBundle\Entity\Faculty;
use FOS\UserBundle\Model\User as BaseUser;


/**
 * User
 *
 * @ORM\Table("ipeer_user")
 * @ORM\Entity(repositoryClass="Ipeer\UserBundle\Entity\UserRepository")
 *
 * @ExclusionPolicy("all")
 */
class User extends BaseUser implements \Serializable
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Expose
     */
    protected $id;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="firstName", type="string", length=255)
     *
     * @Expose
     */
    private $firstName;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="lastName", type="string", length=255)
     *
     * @Expose
     */
    private $lastName;

    /**
     * @var string
     *
     * @Assert\Email()
     * @Assert\NotBlank()
     * @Expose
     */
    protected $email;

    /**
     * @var string
     * @Assert\NotBlank()
     *
     * @Expose
     */
    protected $username;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Ipeer\CourseBundle\Entity\Enrollment", mappedBy="user")
     */
    private $enrollments;


    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Ipeer\CourseBundle\Entity\Faculty", inversedBy="users")
     * @ORM\JoinTable(name="users_faculties")
     */
    private $faculties;


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->enrollments = new ArrayCollection();
        $this->faculties = new ArrayCollection();
    }

    /**
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param Enrollment $enrollment
     * @return User
     */
    public function addEnrollment(Enrollment $enrollment)
    {
        $this->getEnrollments()->add($enrollment);

        return $this;
    }

    /**
     * @param Enrollment $enrollment
     *
     * @return User
     */
    public function removeEnrollment(Enrollment $enrollment)
    {
        $this->getEnrollments()->removeElement($enrollment);

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEnrollments()
    {
        return $this->enrollments;
    }

    /**
     * @param Faculty $enrollment
     * @return User
     */
    public function addFaculty(Faculty $faculty)
    {
        $this->getFaculties()->add($faculty);

        return $this;
    }

    /**
     * @param Faculty $faculty
     *
     * @return User
     */
    public function removeFaculty(Faculty $faculty)
    {
        $this->getFaculties()->removeElement($faculty);

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFaculties()
    {
        if(null === $this->faculties) {
            // needed if object is deserialized and constructor gets bypassed
            $this->faculties = new ArrayCollection();
        }
        return $this->faculties;
    }

    /**
     * @param User $update
     * @return User
     */
    public function merge(User $update)
    {
        $this->setFirstName($update->getFirstName());
        $this->setLastName($update->getLastName());
        $this->setEmail($update->getEmail());
        $this->setUsername($update->getUsername());

        return $this;
    }
}
