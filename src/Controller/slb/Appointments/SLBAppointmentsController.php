<?php
	
	
	namespace App\Controller\slb\Appointments;
	

    use App\Entity\Uitnodiging;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;
	
	class SLBAppointmentsController extends AbstractController
	{
		
		/**
		 * @Route("/slb/uitnodiging/inschrijvingen", name="inschrijvingen")
		 */
		public function inschrijvingen(Request $request)
		{

		    $uitnodiging = $this->getDoctrine()->getRepository(Uitnodiging::class)->findOneBy([

		        'id' => $request->get('id')

            ]);

		    if($uitnodiging === NULL){

		        $this->addFlash('error', 'Er ging iets mis probeer het opnieuw');

		        return $this->redirectToRoute('adminstrator');

            }
		    
		    $afspraken = $uitnodiging->getAfspraken();
			
			return $this->render('slb/appointments/slb_afspraken.html.twig',[

			    'afspraken' => $afspraken

            ]);
		}
		
	}