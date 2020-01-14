<?php
	
	
	namespace App\Controller\SLBer;
	
	
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\Routing\Annotation\Route;
	
	class SLBController extends AbstractController
	{
		
		/**
		 * @Route("/slb/overzicht", name="slber")
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


            return $this->render('slb/slb_inschrijvingen.html.twig');
        }
	
	}