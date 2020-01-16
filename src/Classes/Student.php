<?php
	
	
	namespace App\Classes;
	
	
	class Student
	{
		
		public $naam;
		
		public $studentId;
		
		public function __construct($naam, $studentId)
		{
			
			$this->studentId = $studentId;
			$this->naam = $naam;
			
		}
		
		/**
		 * @return mixed
		 */
		public function getNaam()
		{
			return $this->naam;
		}
		
		/**
		 * @param mixed $naam
		 */
		public function setNaam($naam): void
		{
			$this->naam = $naam;
		}
		
		/**
		 * @return mixed
		 */
		public function getStudentId()
		{
			return $this->studentId;
		}
		
		/**
		 * @param mixed $studentId
		 */
		public function setStudentId($studentId): void
		{
			$this->studentId = $studentId;
		}
		
		
		
	}