<?php
	
	namespace App\Entity;
	
	use App\Repository\UserRepository;
	use App\Traits\UuidEntityTrait;
	use Doctrine\Common\Collections\ArrayCollection;
	use Doctrine\ORM\Mapping as ORM;
	use Symfony\Component\Security\Core\User\UserInterface;
	use Symfony\Component\Uid\Uuid;
	use Symfony\Component\Validator\Constraints as Assert;
	
	/**
	 * @ORM\Entity(repositoryClass=UserRepository::class)
	 * @ORM\Table(name="`user`")
	 */
	class User implements UserInterface
	{
		use UuidEntityTrait;
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
		protected $firstName;
		
		/**
		 *
		 * @ORM\Column(type="string", length=180)
		 */
		protected $lastName;
		
		/**
		 * @ORM\OneToMany(targetEntity=Role::class, mappedBy="users")
		 */
		private $roles;
		
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
		 * @ORM\ManyToOne(targetEntity=Provincia::class, inversedBy="users")
		 */
		private $provincia;
		
		/**
		 * @ORM\ManyToOne(targetEntity=Canton::class, inversedBy="users")
		 */
		private $canton;
		
		/**
		 * @ORM\OneToOne(targetEntity=Image::class, cascade={"persist", "remove"})
		 */
		private $avatar;
		
		
		/**
		 * User constructor.
		 */
		public function __construct ()
		{
			$this->roles = new ArrayCollection();
			$this->uuid = Uuid::v1 ();
		}
		
		
		public function getId (): ?int
		{
			return $this->id;
		}
		
		public function getEmail (): ?string
		{
			return $this->email;
		}
		
		public function setEmail (string $email): self
		{
			$this->email = $email;
			
			return $this;
		}
		
		/**
		 * A visual identifier that represents this user.
		 *
		 * @see UserInterface
		 */
		public function getUsername (): string
		{
			return (string)$this->email;
		}
		
		/**
		 * @see UserInterface
		 */
		public function getRoles (): array
		{
			$rolenames = [];
			foreach ($this->roles as $role)
				if ($role instanceof Role)
					$rolenames [] = $role->getRolename ();
			
			return count ($rolenames) > 0 ? $rolenames : [ 'ROLE_USER' ];
		}
		
		/**
		 * @param Role $role
		 *
		 * @return User
		 */
		public function addRole (Role $role): self
		{
			if (!$this->roles->contains ($role)) {
				$this->roles->add ($role);
			}
		}
		
		/**
		 * @param $roles
		 *
		 * @return User
		 */
		public function setRoles ($roles): self
		{
			$this->roles = $roles;
			
			return $this;
		}
		
		/**
		 * @see UserInterface
		 */
		public function getPassword (): string
		{
			return (string)$this->password;
		}
		
		public function setPassword (string $password): self
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
		public function getSalt (): ?string
		{
			return null;
		}
		
		/**
		 * @see UserInterface
		 */
		public function eraseCredentials ()
		{
			// If you store any temporary, sensitive data on the user, clear it here
			// $this->plainPassword = null;
		}
		
		/**
		 * @return mixed
		 */
		public function getCountry ()
		{
			return $this->country;
		}
		
		/**
		 * @param mixed $country
		 */
		public function setCountry ($country): void
		{
			$this->country = $country;
		}
		
		/**
		 * @return mixed
		 */
		public function getName ()
		{
			return $this->name;
		}
		
		/**
		 * @param mixed $name
		 *
		 * @return User
		 */
		public function setName ($name)
		{
			$this->name = $name;
			return $this;
		}
		
		/**
		 * @return mixed
		 */
		public function getFirstName ()
		{
			return $this->firstName;
		}
		
		/**
		 * @param mixed $firstName
		 *
		 * @return User
		 */
		public function setFirstName ($firstName)
		{
			$this->firstName = $firstName;
			return $this;
		}
		
		/**
		 * @return mixed
		 */
		public function getLastName ()
		{
			return $this->lastName;
		}
		
		/**
		 * @param mixed $lastName
		 *
		 * @return User
		 */
		public function setLastName ($lastName)
		{
			$this->lastName = $lastName;
			return $this;
		}
		
		/**
		 * @return mixed
		 */
		public function getCity ()
		{
			return $this->city;
		}
		
		/**
		 * @param mixed $city
		 */
		public function setCity ($city): void
		{
			$this->city = $city;
		}
		
		public function getStudent (): ?Estudiante
		{
			return $this->student;
		}
		
		public function setStudent (?Estudiante $student): self
		{
			$this->student = $student;
			
			return $this;
		}
		
		public function getProfesor (): ?Profesor
		{
			return $this->profesor;
		}
		
		public function setProfesor (?Profesor $profesor): self
		{
			$this->profesor = $profesor;
			
			return $this;
		}
		
		public function getScoholName (): ?string
		{
			return $this->scoholName;
		}
		
		public function setScoholName (string $scoholName): self
		{
			$this->scoholName = $scoholName;
			
			return $this;
		}
		
		
		public function getScoholLocality (): ?string
		{
			return $this->scoholLocality;
		}
		
		public function setScoholLocality (string $scoholLocality): self
		{
			$this->scoholLocality = $scoholLocality;
			
			return $this;
		}
		
		public function getProvincia (): ?Provincia
		{
			return $this->provincia;
		}
		
		public function setProvincia (?Provincia $provincia): self
		{
			$this->provincia = $provincia;
			
			return $this;
		}
		
		public function getCanton (): ?Canton
		{
			return $this->canton;
		}
		
		public function setCanton (?Canton $canton): self
		{
			$this->canton = $canton;
			
			return $this;
		}
		
		public function getAvatar (): ?Image
		{
			return $this->avatar;
		}
		
		public function setAvatar (?Image $avatar): self
		{
			$this->avatar = $avatar;
			
			return $this;
		}
		
	
		public function __toString ()
		{
			return $this->name;
		}
		
		
	}
