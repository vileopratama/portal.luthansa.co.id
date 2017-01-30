<?php
if (!function_exists('be_regarded')) {
	function be_regarded($x) {
        $number = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
		if ($x < 12)
			return " " . $number[$x];
		elseif ($x < 20)
			return be_regarded($x - 10) . " Belas";
		elseif ($x < 100)
			return be_regarded($x / 10) . " Puluh" . be_regarded($x % 10);
		elseif ($x < 200)
			return "seratus" . be_regarded($x - 100);
		elseif ($x < 1000)
			return be_regarded($x / 100) . " Ratus" . be_regarded($x % 100);
		elseif ($x < 2000)
			return "seribu" . be_regarded($x - 1000);
		elseif ($x < 1000000)
			return be_regarded($x / 1000) . "Ribu" . be_regarded($x % 1000);
		elseif ($x < 1000000000)
			return be_regarded($x / 1000000) . " Juta" . be_regarded($x % 1000000);
	}
}

if (!function_exists('get_digit')) {
	function get_digit($number) {
		$str_digit = null;
		if($number >= 1 && $number < 10) 
			$str_digit = '000'.$number;
		else if($number >= 10 && $number < 100)
			$str_digit = '00'.$number;
		else if($number>=100 && $number < 1000)
			$str_digit = '0'.$number;
		else if($number>=1000 && $number < 10000)
			$str_digit = $number;
		
		return $str_digit;
		
	}
}

if (!function_exists('get_romawi_number')) {
	function get_romawi_number($number) {
		switch($number) {
			case '01' : $romawi_number = 'I';break;
			case '02' : $romawi_number = 'II';break;	
			case '03' : $romawi_number = 'III';break;	
			case '04' : $romawi_number = 'IV';break;
			case '05' : $romawi_number = 'V';break;
			case '06' : $romawi_number = 'VI';break;
			case '07' : $romawi_number = 'VII';break;
			case '08' : $romawi_number = 'VIII';break;
			case '09' : $romawi_number = 'IX';break;
			case '10' : $romawi_number = 'X';break;
			case '11' : $romawi_number = 'XI';break;
            case '12' : $romawi_number = 'XII';break;
		}
		
		return $romawi_number;
	}
}
