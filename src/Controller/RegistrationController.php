<?php
	
	namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
	use App\Form\UserType;
	use App\Repository\RoleRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
	use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
	
	class RegistrationController extends AbstractController
	{
		private $passwordEncoder;
		
		public function __construct (UserPasswordEncoderInterface $passwordEncoder)
		{
			$this->passwordEncoder = $passwordEncoder;
		}

        /**
         * @Route("/registration", name="registration")
         * @param Request $request
         * @param RoleRepository $repository
         * @return RedirectResponse|Response
         * @throws NonUniqueResultException
         */
		public function index (Request $request, RoleRepository $repository)
		{
			$user = new User();
			
			$form = $this->createForm (UserType::class, $user);
			
			$form->handleRequest ($request);
			
			
			if ($form->isSubmitted () && $form->isValid ()) {
				// Encode the new users password
				$password = $this->passwordEncoder->encodePassword ($user, $user->getPassword());
				$user->setPassword ($password);
				
				$userRoleName = $form->get ('roles')->getData ();
				
				$role = $repository->findByName ($userRoleName);
				
				if($role instanceof Role) $user->addRoleObj ($role);
				
				// Save
				$em = $this->getDoctrine ()->getManager ();
				$em->persist ($user);
				$em->flush ();
				
				return $this->redirectToRoute ('app_login');
			}
			
			return $this->render ('registration/index.html.twig', [
				'form' => $form->createView (),
			]);
		}
	}
