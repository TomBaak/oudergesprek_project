<?php
	
	
	namespace App\Controller\Administrator\Klas;
	
	
	use Doctrine\ORM\EntityManagerInterface;
	use KlasType;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Session\SessionInterface;
	use Symfony\Component\Routing\Annotation\Route;
	
	class AdministratorKlasController extends AbstractController
	{
		
		private $session;
		
		public function __construct(SessionInterface $session)
		{
			$this->session = $session;
		}
		
		/**
		 * @Route("/adimistrator/klas/nieuw", name="administrator_nieuwe_klas")
		 */
		public function administratorNieuweKlas(Request $request, EntityManagerInterface $em, SessionInterface $session)
		{
			
			$form = $this->createForm(KlasType::class);
			
			$form->handleRequest($request);
			if ($form->isSubmitted() && $form->isValid()) {
				
				$klas = $form->getData();
				
				dd($klas);
				
				$em->persist($klas);
				
				$em->flush();
				
				$session->getFlashBag()->add(
					'warning',
					'Het is niet mogelijk om een les in het verleden aan te maken'
				);
				
				return $this->redirectToRoute('administrator');
			}
			
			return $this->render('administrator/Klas/administrator_nieuwe_klas.html.twig', [
				
				'form' => $form->createView()
				
			]);
			
		}
		
		/**
		 * @Route("/adimistrator/slb/nieuweLeerling", name="administrator_nieuwe_leerling")
		 */
		public function administratorNieuweLeerling()
		{
			
			return $this->render('administrator/Klas/administrator_nieuwe_leerling.html.twig');
			
		}
		
	}