<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Traits\UuidEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Traits\TimestampableTrait;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(fields={"username"}, message="Existe un usuario registrado con este nombre")
 * @UniqueEntity(fields={"email"}, message="Existe un correo registrado con este nombre")
 * @ORM\HasLifecycleCallbacks
 */
class User implements UserInterface
{
	use UuidEntityTrait;
	use TimestampableTrait;
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=180, unique=true)
	 */
	private $email;

	/**
	 *
	 * @ORM\Column(type="string", length=180)
	 */
	protected $country;

	/**
	 *
	 * @ORM\Column(type="string", length=180, nullable=true)
	 */
	protected $city;

	/**
	 *
	 * @ORM\Column(type="string", length=180)
	 */
	protected $name;

	/**
	 *
	 * @ORM\Column(type="string", length=180)
	 */
	protected $username;

	/**
	 *
	 * @ORM\Column(type="string", length=180)
	 */
	protected $firstName;

	/**
	 * @var string The hashed password
	 * @ORM\Column(type="string")
	 */
	private $password;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $scoholName;

	/**
	 * @ORM\OneToOne(targetEntity=Estudiante::class, cascade={"persist", "remove"})
	 */
	private $student;

	/**
	 * @ORM\OneToOne(targetEntity=Profesor::class, cascade={"persist", "remove"})
	 */
	private $profesor;

	/**
	 *
	 * @ORM\Column(type="string", length=180)
	 */
	private $provincia;

	/**
	 *
	 * @ORM\Column(type="string", length=180)
	 */
	private $canton;

	/**
	 * @ORM\OneToOne(targetEntity=Image::class, cascade={"persist", "remove"})
	 */
	private $avatar;

	/**
	 * @ORM\ManyToMany(targetEntity=Role::class, inversedBy="users")
	 */
	private $rolesObject;



	/**
	 * User constructor.
	 */
	public function __construct()
	{

		$this->uuid = Uuid::v1();
		$this->rolesObject = new ArrayCollection();
	}


	public function getId(): ?int
	{
		return $this->id;
	}

	public function getEmail(): ?string
	{
		return $this->email;
	}

	public function setEmail(string $email): self
	{
		$this->email = $email;

		return $this;
	}

	/**
	 * A visual identifier that represents this user.
	 *
	 * @see UserInterface
	 */
	public function getusername(): string
	{
		return (string)$this->email;
	}

	/**
	 * @see UserInterface
	 */
	public function getRoles(): array
	{
		$rolenames = [];
		foreach ($this->rolesObject as $role)
			if ($role instanceof Role)
				$rolenames[] = $role->getRolename();

		return count($rolenames) > 0 ? $rolenames : ['ROLE_USER'];
	}

	/**
	 * @param Role $role
	 *
	 * @return User
	 */
	public function addRoleObj(Role $role): self
	{
		if ($this->rolesObject instanceof Collection && !$this->rolesObject->contains($role)) {

			die;
			$this->rolesObject->add($role);
		} else if (is_array($this->rolesObject))
			$this->rolesObject[] = $role;
		return $this;
	}

	/**
	 * @param $rolesObject
	 *
	 * @return User
	 */
	public function setRoles($rolesObject): self
	{
		$this->rolesObject = $rolesObject;

		return $this;
	}

	/**
	 * @see UserInterface
	 */
	public function getPassword(): string
	{
		return (string)$this->password;
	}

	public function setPassword(string $password): self
	{
		$this->password = $password;

		return $this;
	}

	/**
	 * Returning a salt is only needed, if you are not using a modern
	 * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
	 *
	 * @see UserInterface
	 */
	public function getSalt(): ?string
	{
		return null;
	}

	/**
	 * @see UserInterface
	 */
	public function eraseCredentials()
	{
		// If you store any temporary, sensitive data on the user, clear it here
		// $this->plainPassword = null;
	}

	/**
	 * @return mixed
	 */
	public function getCountry()
	{
		return $this->country;
	}

	/**
	 * @param mixed $country
	 */
	public function setCountry($country): void
	{
		$this->country = $country;
	}

	/**
	 * @return mixed
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param mixed $name
	 *
	 * @return User
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	public function setusername($username)
	{
		$this->username = $username;
		return $this;
	}


	/**
	 * @return mixed
	 */
	public function getFirstName()
	{
		return $this->firstName;
	}

	/**
	 * @param mixed $firstName
	 *
	 * @return User
	 */
	public function setFirstName($firstName)
	{
		$this->firstName = $firstName;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getCity()
	{
		return $this->city;
	}

	/**
	 * @param mixed $city
	 */
	public function setCity($city): void
	{
		$this->city = $city;
	}

	public function getStudent(): ?Estudiante
	{
		return $this->student;
	}

	public function setStudent(?Estudiante $student): self
	{
		$this->student = $student;

		return $this;
	}

	public function getProfesor(): ?Profesor
	{
		return $this->profesor;
	}

	public function setProfesor(?Profesor $profesor): self
	{
		$this->profesor = $profesor;

		return $this;
	}

	public function getScoholName(): ?string
	{
		return $this->scoholName;
	}

	public function setScoholName(string $scoholName): self
	{
		$this->scoholName = $scoholName;

		return $this;
	}


	public function getScoholLocality(): ?string
	{
		return $this->scoholLocality;
	}

	public function setScoholLocality(string $scoholLocality): self
	{
		$this->scoholLocality = $scoholLocality;

		return $this;
	}

	public function getProvincia(): ?Provincia
	{
		return $this->provincia;
	}

	public function setProvincia($provincia): self
	{
		$this->provincia = $provincia;

		return $this;
	}

	public function getCanton()
	{
		return $this->canton;
	}

	public function setCanton($canton): self
	{
		$this->canton = $canton;

		return $this;
	}

	public function getAvatar(): ?Image
	{
		return $this->avatar;
	}

	public function setAvatar(?Image $avatar): self
	{
		$this->avatar = $avatar;

		return $this;
	}


	public function __toString()
	{
		return $this->name;
	}

	/**
	 * @return Collection|Role[]
	 */
	public function getRolesObject(): Collection
	{
		return $this->rolesObject;
	}

	public function addRolesObject(Role $rolesObject): self
	{
		if (!$this->rolesObject->contains($rolesObject)) {
			$this->rolesObject[] = $rolesObject;
		}

		return $this;
	}

	public function removeRolesObject(Role $rolesObject): self
	{
		$this->rolesObject->removeElement($rolesObject);

		return $this;
	}
}
