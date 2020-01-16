<?php
	
	
	namespace App\Controller\SLBer;
	
	
	use App\Entity\Klas;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\Routing\Annotation\Route;
	
	class SLBController extends AbstractController
	{
		
		/**
		 * @Route("/slb/overzicht", name="slb")
		 */
		public function home()
		{
			
			return $this->render('slb/slb.html.twig');
		}
	
	}