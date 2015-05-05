

<?php
include_once ("function.php");

include_once ("helper.php");

$headers = apache_request_headers();
$method = $_REQUEST['requestName'];
switch ($method)
	{
case "getHscore":
	$height = $_REQUEST['height'];
	$weight = $_REQUEST['weight'];
	getHscore($weight, $height);
	break;

case "calculateHscore":
	$height = $_REQUEST['height'];
	$weight = $_REQUEST['weight'];
	$userid = $_REQUEST['userid'];
	calculateHscore($userid, $weight, $height);
	break;

case "getCategories":
	getCategories();
	break;

case "resetPassword":
	$email = $_REQUEST['email'];
	resetPassword($email);
	break;

case "changePassword":
	$email = $_REQUEST['email'];
	$password = $_REQUEST['password'];
	changePassword($email, $password);
	break;

case "saveCategoryValue":
	$value = $_REQUEST['value'];
	$userid = $_REQUEST['userid'];
	$cat_name = $_REQUEST['category'];
	saveCategoryValue($userid, $cat_name, $value);
	break;

case "getTotalCholesterol":
	$ldl = $_REQUEST['ldl'];
	$hdl = $_REQUEST['hdl'];
	$trig = $_REQUEST['trig'];
	getTotalCholesterol($ldl, $hdl, $trig);
	break;

case "getGraphValues":
	$userid = $_REQUEST['userid'];
	getGraphValues($userid);
	break;

case "getAllHospitals":
	getAllHospitals();
	break;

case "getBMI":
	$height = $_REQUEST['height'];
	$weight = $_REQUEST['weight'];
	$userid = $_REQUEST['userid'];
	getBMI($userid, $weight, $height);
	break;

case "signup":
	$username = $_REQUEST['username'];
	$email = $_REQUEST['email'];
	$pass = $_REQUEST['password'];
	$usertype = $_REQUEST['usertype'];
	$usertype = 0;
	registration($username, $email, $pass, $usertype);
	break;

case "login":
	$username = $_REQUEST['username'];
	$pass = $_REQUEST['password'];
	login($username, $pass);
	break;

case "getAllUsers":
	getAllUsers();
	break;

case "editUser":
	$id = $_REQUEST['userid'];
	$username = $_REQUEST['username'];
	$email = $_REQUEST['email'];
	$pass = $_REQUEST['password'];
	editUser($id, $username, $email, $pass);
	break;

case "getnearbylocation":
	$lat = $_REQUEST['latitude'];
	$lng = $_REQUEST['longitude'];
	getnearbylocation($lat, $lng);
	break;

case "addlocation":
	$name = $_REQUEST['name'];
	$phone = $_REQUEST['phone'];
	$email = $_REQUEST['email'];
	$clinic = $_REQUEST['clinic'];
	$category_id = $_REQUEST['category_id'];
	$address = $_REQUEST['address'];
	$address_line2 = $_REQUEST['address_line2'];
	$city = $_REQUEST['city'];
	$state = $_REQUEST['state'];
	$zip = $_REQUEST['zip'];
	$zipext = $_REQUEST['zipext'];
	$available_hours = $_REQUEST['available_hours'];
	$googlemap_url = $_REQUEST['googlemap_url'];
	$picture_url = $_REQUEST['picture_url'];
	$website_url = $_REQUEST['website_url'];
	$updated_by = $_REQUEST['updated_by'];
	$longitude = $_REQUEST['longitude'];
	$latitude = $_REQUEST['latitude'];
	addlocation($name, $phone, $email, $clinic, $category_id, $address, $address_line2, $city, $state, $zip, $zipext, $available_hours, $googlemap_url, $picture_url, $website_url, $updated_by, $longitude, $latitude);
	break;
	}

?>

