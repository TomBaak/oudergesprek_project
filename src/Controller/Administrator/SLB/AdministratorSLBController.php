<?php
	
	
	namespace App\Controller\Administrator\SLB;
	
	use App\Classes\RandomPassword;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\Routing\Annotation\Route;
	use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
	use UserType;
	
	class AdministratorSLBController extends AbstractController
	{
		
		private $passwordEncoder;
		
		public function __construct(UserPasswordEncoderInterface $passwordEncoder)
		{
			
			$this->passwordEncoder = $passwordEncoder;
			
		}
		
		/**
		 * @Route("/adimistrator/slb/nieuw", name="administrator_nieuwe_sbler")
		 */
		public function administratorNieuweSbler(Request $request, EntityManagerInterface $em, RandomPassword $randomPassword)
		{
			$form = $this->createForm(UserType::class);
			
			$form->handleRequest($request);
			if ($form->isSubmitted() && $form->isValid()) {
				
				$user = $form->getData();
				
				$user->setUsername($user->getEmail());
				
				$user->setRoles(["ROLE_SLB"]);
				
				$user->setIsAdmin(false);
				
				$user->setIsPwChanged(false);
				
				$user->setPassword($randomPassword->getRandomPassword());
				
				dd($user);
				
				$em->persist($user);
				
				$em->flush();
				
				
				return $this->redirectToRoute('task_success');
			}
			
			return $this->render('administrator/SLB/administrator_nieuwe_slber.html.twig', [
				
				'form' => $form->createView()
				
			]);
			
		}
		
	}