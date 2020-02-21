<?php
	
	
	namespace App\Controller\Administrator\Klas;
	
	
	use App\Entity\Klas;
	use App\Entity\Student;
	use App\Entity\Uitnodiging;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Routing\Annotation\Route;
	
	class AdministratorKlasController extends AbstractController
	{
		/**
		 * @Route("/administrator/klassen", name="administrator_klassen")
		 */
		public function administratorKlassen(){
		
			$klassen = $this->getDoctrine()->getRepository(Klas::class)->findAll();
			
			return $this->render('administrator/klas/administrator_klassen.html.twig',[
				
				'klassen' => $klassen
				
			]);
			
		}
		
		/**
		 * @Route("/administrator/klas/studenten", name="administrator_studenten")
		 */
		public function administratorStudenten(Request $request){
			
			$klas = $this->getDoctrine()->getRepository(Klas::class)->findOneBy(['id' => $request->get('id')]);
			
			if($klas === NULL){
				
				$this->addFlash('error', 'Er is een fout opgetreden probeer het nog eens');
				
				$this->redirectToRoute('administrator_klassen');
				
			}
			
			$studenten = $this->getDoctrine()->getRepository(Student::class)->findBy(['klas' => $klas]);
			
			if($studenten === NULL){
				
				$this->addFlash('error', 'Er zijn nog geen studenten toegevoegd aan deze klas');
				
				$this->redirectToRoute('administrator_klassen');
				
			}
			
			return $this->render('user/klas/studenten.html.twig',[
				
				'klas' => $klas,
				'studenten' => $studenten
				
				]);
			
		}
		
		/**
		 * @Route("/administrator/klas/uitnodigingen", name="administrator_klas_uitnodigingen")
		 */
		public function administratorKlasUitnodigingen(Request $request){
			
			$klas = $this->getDoctrine()->getRepository(Klas::class)->findOneBy(['id' => $request->get('id')]);
			
			if($klas === NULL){
				
				$this->addFlash('error', 'Er is een fout opgetreden probeer het nog eens');
				
				$this->redirectToRoute('administrator_klassen');
				
			}
			
			$uitnodigingen = $this->getDoctrine()->getRepository(Uitnodiging::class)->findBy(['klas' => $klas]);
			
			return $this->render('user/uitnodigingen/uitnodigingen.html.twig',[
				
				'klas' => $klas,
				'uitnodigingen' => $uitnodigingen
			
			]);
			
		}
		
	}