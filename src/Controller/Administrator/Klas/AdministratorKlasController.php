<?php
	
	
	namespace App\Controller\Administrator\Klas;
	
	
	use App\Classes\Student;
	use App\Entity\Klas;
	use Doctrine\ORM\EntityManagerInterface;
	use http\Env\Response;
	use KlasType;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\JsonResponse;
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
				
				$em->persist($klas);
				
				$em->flush();
				
				$this->addFlash('success', 'TODO: FIX ALERT MESSAGE');
				
				return $this->redirectToRoute('administrator');
			}
			
			return $this->render('administrator/Klas/administrator_nieuwe_klas.html.twig', [
				
				'form' => $form->createView()
				
			]);
			
		}
		
		/**
		 * @Route("/adimistrator/klas/nieuweStudent/{id}", name="administrator_nieuwe_student")
		 */
		public function administratorNieuweLeerling(Request $request, $id)
		{
			$klas = $this->getDoctrine()->getRepository(Klas::class)->findOneBy([
				
				'id' => $id
				
				]);
			
			return $this->render('administrator/Klas/administrator_nieuwe_student.html.twig', [
				
				'klas' => $klas
				
			]);
			
		}
		
		/**
		 * @Route("/adimistrator/klas/nieuweStudent/{id}/studentToevoegen", methods={"POST"}, name="administrator_nieuwe_student_toevoegen")
		 */
		public function administratorNieuweLeerlingToevoegen($id, EntityManagerInterface $em, Request $request)
		{
			$nieuweStudent = new Student($request->get('naam'), $request->get('studentId'));
			
			$nieuweStudent = json_encode($nieuweStudent);
			
			$klas = $this->getDoctrine()->getRepository(Klas::class)->findOneBy([
				
				'id' => $id
				
			]);
			
			$klas->addLeerling($nieuweStudent);
			
			$em->persist($klas);
			
			$em->flush();
			
			return new JsonResponse(['leerling' => $nieuweStudent ]);
		}
		
		
	}