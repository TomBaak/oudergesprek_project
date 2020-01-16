<?php
	
	
	namespace App\Controller\SLBer\Invitations;
	
	
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\Routing\Annotation\Route;
	
	class SLBInvitationsController extends AbstractController
	{
		
		/**
		 * @Route("/slb/uitnodiging/nieuw", name="uitnodiging")
		 */
		public function uitnodiging()
		{
			
			
			return $this->render('slb/slb_nieuwe_uitnodiging.html.twig');
		}
		
	}