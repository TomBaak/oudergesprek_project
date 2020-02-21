<?php
	
	
	namespace App\Controller\Slb\Uitnodingingen;
	
	
	use App\Entity\Klas;
	use App\Entity\Uitnodiging;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\Routing\Annotation\Route;
	
	class SLBUitnodigingenController extends AbstractController
	{
		/**
		 * @Route("/slb/uitnodigingen", name="slb_uitnodigingen")
		 */
		public function slbUitnodigingen()
		{
			$klassen = $this->getDoctrine()->getRepository(Klas::class)->findBy(['slb' => $this->getUser()->getId()]);
			
			$uitnodigingen = $this->getDoctrine()->getRepository(Uitnodiging::class)->findBy(['klas' => $klassen ]);
			
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
			
			return $this->render('user/uitnodigingen/uitnodigingen.html.twig',[
				
				'uitnodigingen' => $uitnodigingen,
                'klas' => NULL
			
			]);
		}
	}