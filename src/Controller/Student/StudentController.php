<?php
	
	
	namespace App\Controller\Student;
	
	
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\Routing\Annotation\Route;
	
	class StudentController extends AbstractController
	{
		/**
		 * @Route("/student/inschrijving", name="inschrijven")
		 */
		public function inschrijving(){
		
			return $this->render('student/inschrijven.html.twig');
			
		}
		
	}