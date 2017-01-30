<?php
if (!function_exists('get_time_miliseconds')) {
	function get_time_miliseconds() {
        $micro_time = microtime(true);
        $milliseconds = round($micro_time * 1000);
        return $milliseconds;
    }
}

if (!function_exists('get_range_date')) {
	function get_range_date($from_date = null,$to_date = null) {
		$total_range = ((abs(strtotime ($to_date) - strtotime ($from_date)))/(60*60*24));
		return $total_range + 1;
	}
}

if (!function_exists('get_begin_month')) {
	function get_begin_month() {
		$day  = '01';
		$month = date('m');
		$year = date('Y');
		return $day.'/'.$month.'/'.$year;
	}
}


if (!function_exists('get_end_month')) {
	function get_end_month() {
		$month = date('m');
		$year = date('Y');
		
		switch($month) {
			case '01' : $day = 31;break;
			case '02' : $day = $year % 4 == 0 ? 29 : 28; break;
			case '03' : $day = 31;break;
			case '04' : $day = 30;break;
			case '05' : $day = 31;break;
			case '06' : $day = 30;break;
			case '07' : $day = 31;break;
			case '08' : $day = 31;break;
			case '09' : $day = 30;break;
			case '10' : $day = 30;break;
			case '11' : $day = 30;break;
			case '12' : $day = 31;break;
		}
		$day  = $day;
		
		return $day.'/'.$month.'/'.$year;
	}
}

if (!function_exists('get_end_day')) {
    function get_end_day($month,$year) {
        switch($month) {
            case '01' : $day = 31;break;
            case '02' : $day = $year % 4 == 0 ? 29 : 28; break;
            case '03' : $day = 31;break;
            case '04' : $day = 30;break;
            case '05' : $day = 31;break;
            case '06' : $day = 30;break;
            case '07' : $day = 31;break;
            case '08' : $day = 31;break;
            case '09' : $day = 30;break;
            case '10' : $day = 30;break;
            case '11' : $day = 30;break;
            case '12' : $day = 31;break;
        }

        return $day;
    }
}

if (!function_exists('get_list_month')) {
    function get_list_month() {
        $data = array (
            '01' => Lang::get('global.january'),
            '02' => Lang::get('global.february'),
            '03' => Lang::get('global.march'),
            '04' => Lang::get('global.april'),
            '05' => Lang::get('global.may'),
            '06' => Lang::get('global.june'),
            '07' => Lang::get('global.july'),
            '08' => Lang::get('global.august'),
            '09' => Lang::get('global.september'),
            '10' => Lang::get('global.october'),
            '11' => Lang::get('global.november'),
            '12' => Lang::get('global.december'),
        );

        return $data;
    }

}

if (!function_exists('get_list_year')) {
    function get_list_year() {
        $data = array();
        $end_year = date('Y');
        $start_year = 2016;

        for($i=$start_year;$i<=$end_year;$i++) {
            $data[$i] =  $i;
        }
        return $data;
    }

}

if (!function_exists('get_addition_date')) {
    function get_addition_date($from_date,$total) {
        $total = $total-1;
        $from_date = $from_date;// start of definition
        $to_date = date('Y-m-d', strtotime("+$total days", strtotime($from_date)));
        return $to_date;
    }
}

if (!function_exists('get_day_digit')) {
    function get_day_digit($day) {
        $str_digit = null;
        if($day > 0 && $day < 10)
            $str_digit = '0'.$day;
        else
            $str_digit = $day;
        return $str_digit;

    }
}

if (!function_exists('get_month_name')) {
    function get_month_name($month) {
        $data = array (
            '01' => Lang::get('global.january'),
            '02' => Lang::get('global.february'),
            '03' => Lang::get('global.march'),
            '04' => Lang::get('global.april'),
            '05' => Lang::get('global.may'),
            '06' => Lang::get('global.june'),
            '07' => Lang::get('global.july'),
            '08' => Lang::get('global.august'),
            '09' => Lang::get('global.september'),
            '10' => Lang::get('global.october'),
            '11' => Lang::get('global.november'),
            '12' => Lang::get('global.december'),
        );

        return $data[$month];
    }

}