<?php
	
	
	namespace App\Controller\slb;
	
	
	use App\Entity\Klas;
	use App\Entity\Uitnodiging;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\Routing\Annotation\Route;
	
	class SLBController extends AbstractController
	{
		
		/**
		 * @Route("/slb/overzicht", name="slb")
		 */
		public function home()
		{
			$klassen = $this->getDoctrine()->getRepository(Klas::class)->findBy(['slb' => $this->getUser()->getId()]);
			
			$uitnodigingen = $this->getDoctrine()->getRepository(Uitnodiging::class)->findBy(['klas' => $klassen ]);
			
			return $this->render('slb/slb.html.twig',[
				
				'invitations' => $uitnodigingen
				
			]);
		}
	
	}