<?php
	
	
	namespace App\Controller\Administrator\SLB;
	
	use App\Classes\Randomizer;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Session\SessionInterface;
	use Symfony\Component\Routing\Annotation\Route;
	use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
	use UserType;
	
	class AdministratorSLBController extends AbstractController
	{
		
		private $passwordEncoder;
		private $session;
		
		public function __construct(UserPasswordEncoderInterface $passwordEncoder, SessionInterface $session)
		{
			
			$this->session = $session;
			$this->passwordEncoder = $passwordEncoder;
			
		}
		
		/**
		 * @Route("/adimistrator/slb/nieuw", name="administrator_nieuwe_sbler")
		 */
		public function administratorNieuweSbler(Request $request, EntityManagerInterface $em, Randomizer $randomPassword, SessionInterface $session)
		{
			$form = $this->createForm(UserType::class);
			
			$form->handleRequest($request);
			if ($form->isSubmitted() && $form->isValid()) {
				
				$user = $form->getData();
				
				$user->setUsername($user->getEmail());
				
				$user->setRoles(["ROLE_SLB"]);
				
				$user->setIsAdmin(false);
				
				$randomSetPw = $randomPassword->getRandomPassword();
				
				$user->setPassword($this->passwordEncoder->encodePassword($user  ,$randomSetPw));
				
				$em->persist($user);
				
				$em->flush();
				
				$this->addFlash('success', 'Nieuw SLB account aan gemaakt met wachtwoord: '. $randomSetPw);
				
				return $this->redirectToRoute('administrator');
			}
			
			return $this->render('administrator/SLB/administrator_nieuwe_slber.html.twig', [
				
				'form' => $form->createView()
				
			]);
			
		}
		
	}