<?php
	
	
	namespace App\Controller\Administrator;
	
	
	use App\Entity\Klas;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\Routing\Annotation\Route;
	
	class AdministratorController extends AbstractController
	{
		
		/**
		 * @Route("/adimistrator/overzicht", name="administrator")
		 */
		public function administrator()
		{
			$classes = $this->getDoctrine()->getRepository(Klas::class)->findAll();
			
			return $this->render('administrator/administrator.html.twig',[
				
				'classes' => $classes
			
			]);
			
		}
		
		
	}