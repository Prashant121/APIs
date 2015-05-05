<?php
function GUID()
{
	if (function_exists('com_create_guid') === true)
	{
		return trim(com_create_guid(), '{}');
	}

	return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}

function to_utf8( $string ) {
	// From http://w3.org/International/questions/qa-forms-utf-8.html
	if ( preg_match('%^(?:
			[\x09\x0A\x0D\x20-\x7E]            # ASCII
			| [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
			| \xE0[\xA0-\xBF][\x80-\xBF]         # excluding overlongs
			| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
			| \xED[\x80-\x9F][\x80-\xBF]         # excluding surrogates
			| \xF0[\x90-\xBF][\x80-\xBF]{2}      # planes 1-3
			| [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
			| \xF4[\x80-\x8F][\x80-\xBF]{2}      # plane 16
	)*$%xs', $string) ) {
	return $string;
	} else {
		return iconv( 'CP1252', 'UTF-8', $string);
	}
}
function clean($string) {


	return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
} 

function time_ago($date)
{

	if (empty($date)) { 

		return "No date provided";

	}

	$periods = array(
			"second",
			"minute",
			"hour",
			"day",
			"week",
			"month",
			"year",
			"decade"
	);

	$lengths = array(
			"60",
			"60",
			"24",
			"7",
			"4.35",
			"12",
			"10"
	);
	date_default_timezone_set("UTC");
	$now = time();



	$unix_date = strtotime($date);

	// check validity of date

	if (empty($unix_date)) {

		return "Bad date";

	}

	// is it future date or past date

	if ($now > $unix_date) {

		$difference = $now - $unix_date;

		$tense = "ago";

	} else {

		$difference = $unix_date - $now;
		$tense      = "from now";
	}

	for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {

		$difference /= $lengths[$j];

	}

	$difference = round($difference);

	if ($difference != 1) {

		$periods[$j] .= "s";

	}

	return "$difference $periods[$j] {$tense}";

}

function generateResponse($issuccess, $message = NULL, $data = NULL)
{
	$json              = array();
	$json['IsSuccess'] = $issuccess;

	if ($message != NULL)
		$json['message'] = $message;

	if ($data != NULL)
		$json['item'] = $data;

	return $json;
}

function getStatusCode($newcode = NULL)
{
	static $code = 200;
	if ($newcode !== NULL) {
		header('X-PHP-Response-Code: ' . $newcode, true, $newcode);
		if (!headers_sent())
			$code = $newcode;
	}
	return $code;
}


?>