<?php
	
	
	namespace App\Controller\Slb\Klas;
	
	use App\Entity\Klas;
	use App\Entity\Location;
	use App\Entity\Student;
	use App\Entity\Uitnodiging;
	use App\Forms\StudentenType;
	use App\Forms\StudentType;
	use Doctrine\ORM\EntityManagerInterface;
	use http\Env\Response;
	use KlasType;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\JsonResponse;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Session\SessionInterface;
	use Symfony\Component\Mailer\Bridge\Google\Transport\GmailSmtpTransport;
	use Symfony\Component\Mailer\Mailer;
	use Symfony\Component\Routing\Annotation\Route;
	
	class SlbKlasController extends AbstractController
	{
		
		private $session;
		
		public function __construct(SessionInterface $session)
		{
			$this->session = $session;
		}
		
		/**
		 * @Route("/slb/klas/nieuw", name="slb_nieuwe_klas")
		 */
		public function administratorNieuweKlas(Request $request, EntityManagerInterface $em, SessionInterface $session)
		{
			
			if (!$this->getDoctrine()->getRepository(Location::class)->findAll()) {
				$this->addFlash('error', 'Er zijn geen locaties om klassen voor aan te maken. Neem contact op met de administratie');
				
				return $this->redirectToRoute('slb');
			}
			
			$form = $this->createForm(KlasType::class);
			
			$form->handleRequest($request);
			if ($form->isSubmitted() && $form->isValid()) {
				
				$klas = $form->getData();
				
				$klas->setSlb($this->getUser());
				
				try {
					$em->persist($klas);
					
					$em->flush();
				} catch (\Exception $e) {
					error_log($e->getMessage(), 0);
					
					$this->addFlash('error', 'Er ging iets mis tijdens het aanmaken van de klas probeer het alstublieft nog eens');
					
					return $this->redirectToRoute('slb');
				}
				
				$this->addFlash('success', 'Klas ' . $klas->getNaam() . ' toegevoegd');
				
				return $this->redirectToRoute('slb');
			}
			
			return $this->render('slb/Klas/slb_nieuwe_klas.html.twig', [
				
				'form' => $form->createView()
			
			]);
			
		}
		
		/**
		 * @Route("/slb/klas/studenten", name="slb_studenten")
		 */
		public function administratorStudenten(Request $request, EntityManagerInterface $em)
		{
			$klas = $this->getDoctrine()->getRepository(Klas::class)->findOneBy([
				
				'id' => $request->get('id')
			
			]);
			
			return $this->render('slb/Klas/slb_studenten.html.twig', [
				
				'klas' => $klas
			
			]);
			
		}
		
		/**
		 * @Route("/slb/klas/studenten/toevoegen", name="slb_studenten_toevoegen")
		 */
		public function administratorStudentenToevoegen(Request $request, EntityManagerInterface $em)
		{
			$form = $this->createForm(StudentenType::class);
			
			$form->handleRequest($request);
			
			if ($form->isSubmitted() && $form->isValid()) {
				
				$result = $form->getData();
				
				$uploadedFile = $result['studentFile'];
				
				$originalFilename = pathinfo($uploadedFile, PATHINFO_FILENAME);
				
				$tempFilePath = $_FILES['studenten']['tmp_name']['studentFile'];
				
				$studentFile = fopen($tempFilePath, 'r');
				$file = fopen($tempFilePath,'r');
				
				$studenten = [];
				
				while (($data = fgetcsv($file, 1000, ",")) !== FALSE)
				{
					// Each individual array is being pushed into the nested array
					$studenten[] = $data;
				}
				
				// Close the file
				fclose($file);
				
				$klas = $this->getDoctrine()->getRepository(Klas::class)->findOneBy(['id' => $request->get('id')]);
				
				for($i = 0; $i < count($studenten); $i++){
					
					$student = new Student();
					
					$student->setNaam($studenten[$i][0] . $studenten[$i][1]);
					$student->setStudentId($studenten[$i][2]);
					$student->setKlas($klas);
					
					$em->persist();
				}
				
				$em->flush();
				
				$this->addFlash('success', 'Leerlingen toegevoegd');
				
				return $this->redirectToRoute('slb_studenten',['id' => $request->get('id')]);
				
			}
			
			return $this->render('slb/Klas/slb_studenten_toevoegen.html.twig', [
				
				'form' => $form->createView()
			
			]);
			
		}
		
		/**
		 * @Route("/slb/klas/{id}/uitnodigingen", name="slb_uitnodigingen")
		 */
		public function slb_uitnodigingen($id, EntityManagerInterface $em)
		{
			
			$uitnodigingen = $this->getDoctrine()->getRepository(Uitnodiging::class)->findBy([
				
				'klas' => $id
			
			]);
			
			return $this->render('slb/uitnodigingen/slb_uitnodigingen.html.twig', [
				
				'uitnodigingen' => $uitnodigingen
			
			]);
			
		}
		
		/**
		 * @Route("/slb/klas/studentVerwijderen", name="slb_student_verwijderen")
		 */
		public function administratorLeerlingVerwijderen(Request $request, EntityManagerInterface $em)
		{
			
			$student = $this->getDoctrine()->getRepository(Student::class)->findOneBy([
				
				'id' => $request->get('id')
			
			]);
			
			$em->remove($student);
			
			$em->flush();
			
			$this->addFlash('success', 'Student ' . $student->getNaam() . ' verwijderd');
			
			return $this->redirectToRoute('slb_studenten', array('id' => $request->get('klasId')));
			
		}
		
	}