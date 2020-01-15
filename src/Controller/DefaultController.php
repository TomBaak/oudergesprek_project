<?php
	
	
	namespace App\Controller;
	
	
	use App\Entity\User;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\Routing\Annotation\Route;
	use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
	
	
	class DefaultController extends AbstractController
	{
		
		private $passwordEncoder;
		
		public function __construct(UserPasswordEncoderInterface $passwordEncoder)
		{
			$this->passwordEncoder = $passwordEncoder;
		}
		
		/**
		 * @Route("/", name="home")
		 */
		public function home()
		{
			return $this->render('index.html.twig');
		}
		
		
	}