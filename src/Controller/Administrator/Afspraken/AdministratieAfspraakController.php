<?php
	
	
	namespace App\Controller\Administrator\Afspraken;
	

    use App\Entity\Uitnodiging;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;
	
	class AdministratieAfspraakController extends AbstractController
	{
		
		/**
		 * @Route("/adimistrator/uitnodiging/Afspraken", name="inschrijvingen")
		 */
		public function inschrijvingen(Request $request)
		{

		    $uitnodiging = $this->getDoctrine()->getRepository(Uitnodiging::class)->findOneBy([

		        'id' => $request->get('id')

            ]);

		    if($uitnodiging === NULL){

		        $this->addFlash('error', 'Er ging iets mis probeer het opnieuw');

		        return $this->redirectToRoute('administrator');

            }
		    
		    $afspraken = $uitnodiging->getAfspraken()->toArray();
		    
			usort($afspraken, function($a, $b){
				
				if($a->getTijd() > $b->getTijd()){
					return 1;
				}elseif($a->getTijd() < $b->getTijd()){
					return -1;
				}else{
					return 0;
				}
				
			});
			
			return $this->render('user/afspraken/user_afspraken.html.twig',[

			    'afspraken' => $afspraken,
				'uitnodiging' => $uitnodiging

            ]);
		}
		
	}