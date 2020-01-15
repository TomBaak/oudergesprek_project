<?php
	
	
	namespace App\Controller\Administrator\Klas;
	
	
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\Routing\Annotation\Route;
	
	class AdministratorKlasController extends AbstractController
	{
		
		/**
		 * @Route("/adimistrator/klas/nieuw", name="administrator_nieuwe_klas")
		 */
		public function administratorNieuweKlas()
		{
			
			return $this->render('administrator/Klas/administrator_nieuwe_klas.html.twig');
			
		}
		
		/**
		 * @Route("/adimistrator/slb/nieuweLeerling", name="administrator_nieuwe_leerling")
		 */
		public function administratorNieuweLeerling()
		{
			
			return $this->render('administrator/Klas/administrator_nieuwe_leerling.html.twig');
			
		}
		
	}