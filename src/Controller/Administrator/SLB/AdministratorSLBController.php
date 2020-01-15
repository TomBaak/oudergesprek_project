<?php
	
	
	namespace App\Controller\Administrator\SLB;
	
	
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\Routing\Annotation\Route;
	
	class AdministratorSLBController extends AbstractController
	{
		
		/**
		 * @Route("/adimistrator/slb/nieuw", name="administrator_nieuwe_sbler")
		 */
		public function administratorNieuweSbler()
		{
			
			return $this->render('administrator/SLB/administrator_nieuwe_slber.html.twig');
			
		}
		
	}