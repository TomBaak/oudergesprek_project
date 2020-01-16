<?php
	
	
	namespace App\Classes;
	
	
	class Student
	{
		
		public $naam;
		
		public $studentId;
		
		public $studentEmail;
		
		public function __construct($naam, $studentId)
		{
			
			$this->studentId = $studentId;
			$this->naam = $naam;
			$this->studentEmail = $studentId . "@student.rocmondriaan.nl";
			
			
		}
		
	}