<?php
	
	
	namespace App\Controller\Slb\Afspraken;
	
	
	use App\Entity\Afspraak;
	use App\Entity\Uitnodiging;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Routing\Annotation\Route;
	
	class SLBAfsprakenController extends AbstractController
	{
		
		/**
		 * @Route("/slb/afspraken", name="slb_afspraken")
		 */
		public function afspraken(Request $request){
			
			$uitnodiging = $this->getDoctrine()->getRepository(Uitnodiging::class)->findOneBy([
				
				'id' => $request->get('id')
				
			]);
			
			if($uitnodiging === NULL){
				$this->addFlash('error', 'Er is een fout opgetreden probeer het alstublieft opnieuw');
				
				return $this->redirectToRoute('home');
			}
			
			$afspraken = $this->getDoctrine()->getRepository(Afspraak::class)->findBy([
				
				'uitnodiging' => $uitnodiging
				
			]);
			
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