<?php
	
	
	namespace App\Controller\SLBer;
	
	
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

        /**
         * @Route("/slb/uitnodiging/inschrijvingen", name="inschrijvingen")
         */
        public function inschrijvingen()
        {


            return $this->render('slb/slb_afspraken.html.twig');
        }
		
		/**
		 * @Route("/slb/uitnodiging/nieuw", name="uitnodiging")
		 */
		public function uitnodiging()
		{
			
			
			return $this->render('slb/slb_nieuwe_uitnodiging.html.twig');
		}
	
	}