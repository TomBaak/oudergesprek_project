<?php
	
	
	namespace App\Controller\SLBer\Invitations;
	
	
	use App\Classes\Randomizer;
	use App\Forms\UitnodigingType;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Routing\Annotation\Route;
	
	class SLBInvitationsController extends AbstractController
	{
		
		/**
		 * @Route("/slb/uitnodiging/nieuw", name="uitnodiging")
		 */
		public function uitnodiging(Request $request, Randomizer $randomizer, EntityManagerInterface $em)
		{
			$form = $this->createForm(UitnodigingType::class);
			
			$form->handleRequest($request);
			if ($form->isSubmitted() && $form->isValid()) {
				
				$uitnodiging = $form->getData();
				
				dd($uitnodiging);
				
				$em->persist($uitnodiging);
				
				$em->flush();
				
				$this->addFlash('success', 'Nieuw SLB account aan gemaakt met wachtwoord: ');
				
				return $this->redirectToRoute('administrator');
			}
			
			return $this->render('slb/invitations/slb_nieuwe_uitnodiging.html.twig',[
				
				'form' => $form->createView()
				
			]);
		}
		
	}