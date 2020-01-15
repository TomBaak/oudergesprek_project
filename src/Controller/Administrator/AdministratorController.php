<?php
	
	
	namespace App\Controller\Administrator;
	
	
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\Routing\Annotation\Route;
	
	class AdministratorController extends AbstractController
	{
		
		/**
		 * @Route("/adimistrator/overzicht", name="administrator")
		 */
		public function administrator()
		{
			
			return $this->render('administrator/administrator.html.twig');
			
		}
		
		
	}