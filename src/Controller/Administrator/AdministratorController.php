<?php
	
	
	namespace App\Controller\Administrator;
	
	
	use App\Entity\Klas;
	use App\Entity\Uitnodiging;
	use DateTime;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\Routing\Annotation\Route;
	
	class AdministratorController extends AbstractController
	{
		
		/**
		 * @Route("/adimistrator/overzicht", name="administrator")
		 */
		public function administrator()
		{
			$uitnodigingen = $this->getDoctrine()->getRepository(Uitnodiging::class)->findAll();
			
			return $this->render('administrator/administrator.html.twig',[
				
				'Uitnodigingen' => $uitnodigingen,
				'vandaag' => new DateTime()
			
			]);
			
		}
		
		
		
	}