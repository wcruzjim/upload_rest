<?php

defined('BASEPATH') || exit('No direct script access allowed');

class FormPersonalValidator
{

	// Valida que el campo ingresado sea una fecha

	public function validateDate($date){
		$dateArray = explode('-',$date);
		$year = isset($dateArray[0]) ? $dateArray[0] : -1;
		$month = isset($dateArray[1]) ? $dateArray[1] : -1;
		$day = isset($dateArray[2]) ? $dateArray[2] : -1;
		if( checkdate($month, $day, $year)){
			return true;
		}else{
			return false;
		}
	}

}