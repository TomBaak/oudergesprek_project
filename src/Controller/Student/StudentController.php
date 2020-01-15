<?php
	
	
	namespace App\Controller\Student;
	
	
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\Routing\Annotation\Route;
	
	class StudentController extends AbstractController
	{
		/**
		 * @Route("/student/afspraak", name="afspraak")
		 */
		public function afspraak(){
		
			return $this->render('student/student_afspraak.html.twig');
			
		}
		
	}