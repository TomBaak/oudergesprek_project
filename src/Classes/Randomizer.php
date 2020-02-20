<?php
	
	
	namespace App\Classes;
	
	
	class Randomizer
	{
		
		public function getRandomPassword($length = 10) {
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$charactersLength = strlen($characters);
			$randomString = '';
			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, $charactersLength - 1)];
			}
			return $randomString;
		}
		
		public function getUitnodigingsCode($startTime, $endTime, $date, $slbId) {
			$invitationCode = $startTime . $endTime . $date . $slbId;
			
			return $invitationCode;
		}
		
	}