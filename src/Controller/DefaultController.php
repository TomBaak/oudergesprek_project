<?php
	
	
	namespace App\Controller;
	
	
	use App\Entity\Uitnodiging;
	use App\Entity\User;
	use Cassandra\Timestamp;
	use DateInterval;
	use DatePeriod;
	use DateTime;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\Routing\Annotation\Route;
	use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
	
	
	class DefaultController extends AbstractController
	{
		
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
				
				'Uitnodigingen' => $uitnodigingen
				
			]);
		}
		
		/**
		 * @Route("/test", name="test")
		 */
		public function test()
		{
			
			$startTime = new DateTime('12:00');
			$stopTime = new DateTime('13:00');
			
			$times = [];
			
			$period = new DatePeriod(
				$startTime,
				new DateInterval('PT15M'),
				$stopTime->modify('+15 minutes')
			);
			
			foreach ($period as $key => $value) {
				$times[] = $value;
			}
			
			die();
		}
		
		
	}