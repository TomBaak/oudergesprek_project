<?php
	
	
	namespace App\Controller;
	
	
	use App\Entity\Uitnodiging;
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
			
			$uitnodigingen = $this->getDoctrine()->getRepository(Uitnodiging::class)->findAll();
			
			usort($uitnodigingen, function($a, $b){
				
				if($a->getDate() > $b->getDate()){
					return 1;
				}elseif($a->getDate() < $b->getDate()){
					return -1;
				}elseif($a->getStartTime() > $b->getStartTime()){
					return 1;
				}elseif($a->getStartTime() < $b->getStartTime()){
					return -1;
				}else{
					return 0;
				}
				
			});
			
			return $this->render('index.html.twig',[
				
				'uitnodigingen' => $uitnodigingen
				
			]);
		}
		
		
	}