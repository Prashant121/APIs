

<?php
include_once ("connect.php");

include_once ("helper.php");

// error_reporting(0);

function getHscore($weight, $height)
	{
	$categoryfactor = 5;
	$variable = 100;
	$bmi = ($weight * 703) / ($height * $height);
	global $conn;
	$query = mysqli_query($conn, 'select * from hscore_keys, hscore_cats, hscore_model where cat_name = "BMI" and
 hscore_keys.model_id = hscore_model.id and hscore_keys.cat_id = hscore_cats.id
 and low_range < ' . $bmi . ' order by low_range desc limit 1 ');
	while ($var = mysqli_fetch_object($query))
		{
		$data['id'] = $var->id;
		$data['model_id'] = $var->model_id;
		$data['cat_id'] = $var->cat_id;
		$data['low_range'] = $var->low_range;
		$data['high_range'] = $var->high_range;
		$data['bmi'] = $bmi;
		$value = $var->value;
		$hfactor = $value * $categoryfactor;
		$data['value'] = $hfactor;
		$data['reccomendation'] = $var->recommendation;
		$data['short_text'] = $var->short_text;
		$json[] = $data;
		}

	$response = generateResponse(TRUE, "HScore", $json);
	echo json_encode($response);
	}

function getGraphValues($userid)
	{
	global $conn;
	$categoryfactor = 5;
	$count = 0;
	$query_bmi = "select * from hscore_values, hscore_cats where hscore_cats.cat_name = 'BMI' and hscore_cats.id = hscore_values.cat_id and hscore_values.updated_by = $userid";
	$bmis = mysqli_query($conn, $query_bmi);
	while ($var = mysqli_fetch_object($bmis))
		{
		$count = 1;
		$hscore = $var->value * $categoryfactor;
		$data["bmi"] = $var->value;
		$data["hscore"] = $hscore;
		$d = explode(" ", $var->updated_on);
		$data["createdate"] = $d[0];
		$json[] = $data;
		}

	if ($count == 1) $response = generateResponse(TRUE, "HScore history for the user", $json);
	  else $response = generateResponse(FALSE, "No history available for this user", null);
	echo json_encode($response);
	}

function calculateHscore($userid, $weight, $height)
	{
	$categoryfactor = 5;
	$variable = 100;
	$bmi = ($weight * 703) / ($height * $height);
	global $conn;
	$query = mysqli_query($conn, 'select * from hscore_keys, hscore_cats, hscore_model where cat_name = "BMI" and
 hscore_keys.model_id = hscore_model.id and hscore_keys.cat_id = hscore_cats.id
 and low_range < ' . $bmi . ' order by low_range desc limit 1 ');
	$response = generateResponse(FALSE, "HScore could not be calculated", null);
	while ($var = mysqli_fetch_object($query))
		{
		$value = $var->value;
		$hfactor = $value * $categoryfactor;
		$response = generateResponse(TRUE, "Calculated HScore", $hfactor);
		}

	echo json_encode($response);
	}

function saveCategoryValue($userid, $catname, $value)
	{
	global $conn;
	$q = "INSERT INTO hscore_values (cat_id, value, updated_by) select id, $value, $userid from hscore_cats where cat_name =  '$catname'";
	$query = mysqli_query($conn, $q) OR die("Error:" . mysqli_error($conn));
	if ($query)
		{
		$response = generateResponse(TRUE, "Successfully added value");
		}
	  else
		{
		$response = generateResponse(FALSE, "Failed to add value");
		}

	echo json_encode($response);
	}

function getTotalCholesterol($ldl, $hdl, $trig)
	{
	$total = $ldl + $hdl + ($trig / 5);
	$response = generateResponse(TRUE, "Total Cholesterol", $total);
	echo json_encode($response);
	}

function getBMI($userid, $weight, $height)
	{
	$bmi = ($weight * 703) / ($height * $height);
	global $conn;
	$q = "INSERT INTO hscore_values (cat_id, value, updated_by) select id, $bmi, $userid from hscore_cats where cat_name =  'BMI'";
	$q2 = "INSERT INTO hscore_values (cat_id, value, updated_by) select id, $height, $userid from hscore_cats where cat_name =  'Height'";
	$q3 = "INSERT INTO hscore_values (cat_id, value, updated_by) select id, $weight, $userid from hscore_cats where cat_name =  'Weight'";
	$query = mysqli_query($conn, $q) OR die("Error:" . mysqli_error($conn));
	$query2 = mysqli_query($conn, $q2) OR die("Error:" . mysqli_error($conn));
	$query3 = mysqli_query($conn, $q3) OR die("Error:" . mysqli_error($conn));
	$response = generateResponse(TRUE, "BMI", $bmi);
	echo json_encode($response);
	}

function getCategories()
	{
	global $conn;
	$check = mysqli_query($conn, "Select * from location_cats ");
	if (mysqli_num_rows($check) > 0)
		{
		while ($var = mysqli_fetch_object($check))
			{
			$data['id'] = $var->id;
			$data['category'] = $var->category;
			$json[] = $data;
			}

		$response = generateResponse(TRUE, "Category List", $json);
		}
	  else
		{
		$response = generateResponse(FALSE, "No Category available", $json);
		}

	echo json_encode($response);
	}

function getAllHospitals()
	{
	global $conn;
	$check = mysqli_query($conn, "Select * from locations ");
	if (mysqli_num_rows($check) > 0)
		{
		while ($var = mysqli_fetch_object($check))
			{
			$data['Lat'] = $var->latitude;
			$data['Long'] = $var->longitude;
			$data['LocationName'] = $var->clinic;
			$data['HospitalType'] = $var->clinic;
			$data['DrName'] = $var->name;
			$data['PhoneNumber'] = $var->phone;
			$data['Email'] = $var->email;
			$data['Address'] = $var->address;
			$data['City'] = $var->city;
			$data['State'] = $var->state;
			$data['WebsiteUrl'] = $var->website_url;
			$data['OpenOfficeHours'] = $var->available_hours;
			$data['ActionCall'] = 'call';
			$data['ActionVideo'] = 'call';

			// On UI this categoryid will be used for differentiating b/w categories.

			$data['CategoryId'] = $var->category_id;
			switch ($var->category_id)
				{
			case 1:
				$data['HospitalType'] = 'hcc';
				break;

			case 2:
				$data['HospitalType'] = 'ucc';
				break;

			case 3:
				$data['HospitalType'] = 'nec';
				break;

			case 4:
				$data['HospitalType'] = 'er';
				break;
				}

			$json[] = $data;
			}

		$response = generateResponse(TRUE, "Hospital List", $json);
		}
	  else
		{
		$response = generateResponse(FALSE, "No Location available", $json);
		}

	echo json_encode($response);
	}

function registration($username, $email, $pass, $usertype)
	{
	$pass = md5($pass);
	global $conn;

	// check user already exist or not

	$query = "select id from users where email='$email'";
	$result = mysqli_query($conn, $query) OR die("Error:" . mysqli_error($conn));

	// if exists return message

	if (mysqli_num_rows($result) > 0)
		{
		$logobj = mysqli_fetch_object($result);
		$response = generateResponse(FALSE, "User already Exists");
		}
	  else
		{
		$registerquery = "insert into users (username,email,enc_pass) values('$username','$email','$pass')";
		$query = mysqli_query($conn, $registerquery) OR die("Error:" . mysqli_error($conn));
		if ($query)
			{
			$response = generateResponse(TRUE, "Successfully Signup");
			}
		  else
			{
			$response = generateResponse(FALSE, "Failed to signup");
			}
		}

	echo json_encode($response);
	}

function login($username, $pass)
	{
	$pass = md5($pass);
	global $conn;
	$check = mysqli_query($conn, "Select id,email,username from users where email='$username' AND enc_pass='$pass'");
	if (mysqli_num_rows($check) > 0)
		{
		while ($var = mysqli_fetch_object($check))
			{
			$data['id'] = $var->id;
			$data['email'] = $var->email;
			$data['username'] = $var->username;
			}

		$response = generateResponse(TRUE, "Login Details", $data);
		}
	  else
		{
		$response = generateResponse(FALSE, "Not exists", $json);
		}

	echo json_encode($response);
	}

function getnearbylocation($lat, $lng)
	{
	global $conn;

	// calculate shortest distance on based of haversine formulae   b/w 2 lat long

	$check = mysqli_query($conn, "SELECT *, SQRT(
    POW(69.1 * (latitude - ($lat)), 2) +
    POW(69.1 * ($lng - longitude) * COS(latitude / 57.3), 2)) AS distance
FROM locations HAVING distance < 25 ORDER BY distance");
	if (mysqli_num_rows($check) > 0)
		{
		while ($var = mysqli_fetch_object($check))
			{
			$data['Distance'] = $var->distance;
			$data['Lat'] = $var->latitude;
			$data['Long'] = $var->longitude;
			$data['LocationName'] = $var->clinic;
			$data['HospitalType'] = $var->clinic;
			$data['DrName'] = $var->name;
			$data['PhoneNumber'] = $var->phone;
			$data['Email'] = $var->email;
			$data['Address'] = $var->address;
			$data['City'] = $var->city;
			$data['State'] = $var->state;
			$data['WebsiteUrl'] = $var->website_url;
			$data['OpenOfficeHours'] = $var->available_hours;
			$data['ActionCall'] = 'call';
			$data['ActionVideo'] = 'call';
			$data['CategoryId'] = $var->category_id;
			switch ($var->category_id)
				{
			case 1:
				$data['HospitalType'] = 'hcc';
				break;

			case 2:
				$data['HospitalType'] = 'ucc';
				break;

			case 3:
				$data['HospitalType'] = 'nec';
				break;

			case 4:
				$data['HospitalType'] = 'er';
				break;
				}

			$json[] = $data;
			}

		$response = generateResponse(TRUE, "Hospital List", $json);
		}
	  else
		{
		$response = generateResponse(FALSE, "No Location available", $json);
		}

	echo json_encode($response);
	}

function getAllUsers()
	{
	global $conn;
	$check = mysqli_query($conn, "SELECT *  from users");
	if (mysqli_num_rows($check) > 0)
		{
		while ($var = mysqli_fetch_object($check))
			{
			$data['id'] = $var->id;
			$data['username'] = $var->username;
			$data['password'] = $var->enc_pass;
			$data['email'] = $var->email;
			$data['verifiedon'] = $var->verified_on;
			$json[] = $data;
			}

		$response = generateResponse(TRUE, "User List", $json);
		}
	  else
		{
		$response = generateResponse(FALSE, "No Users Available", $json);
		}

	echo json_encode($response);
	}

function editUser($id, $username, $email, $pass)
	{
	global $conn;

	// first check user exist or not

	$query = "select id from users where id='$id'";
	$result = mysqli_query($conn, $query) OR die("Error:" . mysqli_error($conn));
	if (mysqli_num_rows($result) > 0)
		{
		$updatequery = "update users set email='$email',enc_pass='$pass',username='$username' where id='$id' ";
		$updateresult = mysqli_query($conn, $updatequery) OR die("Error:" . mysqli_error($conn));
		if ($updateresult)
			{
			$response = generateResponse(TRUE, "Successfully changed details");
			}
		  else
			{
			$response = generateResponse(FALSE, "Not able to make changes");
			}
		}
	  else
		{
		$response = generateResponse(FALSE, "User Not Exists");
		}

	echo json_encode($response);
	}

function resetPassword($email)
	{
	global $conn;
	$query = "select * from users where email = '$email'";
	$result = mysqli_query($conn, $query) OR die("Error:" . mysqli_error($conn));
	if (mysqli_num_rows($result) > 0)
		{
		$to = $email;
		$headers.= "MIME-Version: 1.0\r\n";
		$headers.= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		$subject = "Reset Password";
		$message = "<html><body>Hi, <br/><br/>Please <a href='http://hallmarkmd.com/locations/admin/reset-password.html?$email'>click here</a> to reset your password.<br/><br/>Regards, <br/>Hallmark Team</body></html>";
		if (mail($to, $subject, $message, $headers) == 1) $response = generateResponse(TRUE, "Password reset link has been sent to your email.");
		  else $response = generateResponse(FALSE, "Server error");
		}
	  else $response = generateResponse(FALSE, "This email is not registered with Hallmark");
	echo json_encode($response);
	}

function changePassword($emailid, $pass)
	{
	global $conn;
	$check = "select * from users where email = '$emailid'";
	$result = mysqli_query($conn, $check) OR die("Error:" . mysqli_error($conn));
	if (mysqli_num_rows($result) > 0)
		{
		$pass = md5($pass);
		$q = "UPDATE users set enc_pass = '$pass' where email = '$emailid' OR username = '$emailid'";
		$query = mysqli_query($conn, $q) OR die("Error:" . mysqli_error($conn));
		if ($query)
			{
			$response = generateResponse(TRUE, "Password successfully updated");
			}
		  else
			{
			$response = generateResponse(FALSE, "Email doesn't exists");
			}
		}
	  else $response = generateResponse(FALSE, "Email doesn't exists");
	echo json_encode($response);
	}

function addlocation($name, $phone, $email, $clinic, $category_id, $address, $address_line2, $city, $state, $zip, $zipext, $available_hours, $googlemap_url, $picture_url, $website_url, $updated_by, $longitude, $latitude)
	{
	global $conn;
	$registerquery = "INSERT INTO locations (name, phone, email, clinic, category_id, address,
 address_line2, city, state, zip, zip-ext, available_hours, googlemap_url, 
 picture_url, website_url, updated_by, longitude, latitude) 
 values('$name','$phone','$email','$clinic', $category_id, '$address',
 '$address_line2', '$city', '$state', '$zip', '$zipext', '$available_hours', '$googlemap_url', 
 '$picture_url', '$website_url', $updated_by, $longitude, $latitude)";
	$query = mysqli_query($conn, $registerquery) OR die("Error:" . mysqli_error($conn));
	if ($query)
		{
		$response = generateResponse(TRUE, "Successfully added");
		}
	  else
		{
		$response = generateResponse(FALSE, "Failed to Add");
		}

	echo json_encode($response);
	}

?>

