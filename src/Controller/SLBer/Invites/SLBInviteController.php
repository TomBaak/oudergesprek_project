<?php
	
	
	namespace App\Controller\SLBer\Invites;
	
	
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\Routing\Annotation\Route;
	
	class SLBInviteController extends AbstractController
	{
		
		/**
		 * @Route("/slb/uitnodiging/nieuw", name="nieuwe_uitnodiging")
		 */
		public function home()
		{
			return $this->render('slb/slb.html.twig');
		}
		
	}