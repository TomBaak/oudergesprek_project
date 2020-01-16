<?php
	
	
	namespace App\Controller\SLBer\Appointments;
	
	
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\Routing\Annotation\Route;
	
	class SLBAppointmentsController extends AbstractController
	{
		
		/**
		 * @Route("/slb/uitnodiging/inschrijvingen", name="inschrijvingen")
		 */
		public function inschrijvingen()
		{
			
			
			return $this->render('slb/appointments/slb_afspraken.html.twig');
		}
		
	}