<?php
	/**
	 * Created by PhpStorm.
	 * User: Ubel
	 * Date: 15/02/2021
	 * Time: 6:55 PM
	 */
	
	namespace App\Traits;
	
	use Symfony\Bridge\Doctrine\IdGenerator\UuidV1Generator;
	use Symfony\Component\Uid\Uuid;
	
	
	trait UuidEntityTrait
	{
		/**
		 * @ORM\Column(type="uuid", unique=true)
		 * @ORM\CustomIdGenerator(class=UuidV1Generator::class)
		 * @ORM\GeneratedValue()
		 */
		private $uuid;
		
		/**
		 * @return string|null
		 */
		public function getUuid(): ?string
		{
			return $this->uuid;
		}
		
		/**
		 * @param string|null $uuid
		 *
		 * @return self
		 */
		public function setUuid(?string $uuid): self
		{
			$this->uuid = $uuid;
			
			return $this;
		}
	}