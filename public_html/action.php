<?php
include 'ChromePhp.php';
session_start();

//for use with javascript unescape function
function encode($input) {
	$temp = ''; 
	$length = strlen($input); 
	for($i = 0; $i < $length; $i++) {
		$temp .= '%' . bin2hex($input[$i]);
	} 
	return $temp; 
}

//if posting only
if(isset($_POST['submit'])) {
	$return = array('type' => 'error', 'session' => $_SESSION);
	$answer = isset($_POST['autovalue']) ? trim($_POST['autovalue']) : false;
	ChromePhp::log($_SESSION['_form_validate']);
	ChromePhp::log($answer);
	//if(!isset($_SESSION['_form_validate']) || !$answer || $_SESSION['_form_validate'] != $answer) {
	if(!in_array(strtolower($answer),array('st thomas','saint thomas','stthomas','saintthomas','st.thomas','st. thomas'),true)) {
		$return['message'] = 'Error validating security question.';
	} else {
		$to = 'dillonke91@gmail.com'; // Change this line to your email.
		$to2 = 'nehag990@gmail.com'; // Change this line to your email.
		
		$name = isset($_POST['name']) ? trim($_POST['name']) : '';
		$email = isset($_POST['email']) ? trim($_POST['email']) : '';
		$persons = isset($_POST['persons']) ? trim($_POST['persons']) : '';
		$attending = isset($_POST['attending']) ? trim($_POST['attending']) : '';
		$reservationNumber = isset($_POST['reservationNumber']) ? trim($_POST['reservationNumber']) : '';
		$message = isset($_POST['message']) ? trim($_POST['message']) : '';
		// $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
		$subject = isset($_POST['subject']) ? trim($_POST['subject']) : 'Contact Form Submission';
		
		if($name && $email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= "From: My Wedding Website <no-replay@" . $_SERVER['SERVER_NAME'] . ">\r\n";
			
			$message .= 'New Signup for your Wedding<br />';
			$message .= ' <br /> Name: ' . $name;
			$message .= ' <br /> Email: ' . $email;
			if($persons) {
				$message .= ' <br /> Number of Persons: ' . $persons;
			}
			if($attending) {
				$message .= ' <br />RSVP Status: ' . $attending;
			}
			$message .= '<br /> RSVP #: ' . $reservationNumber;

			@$send = mail($to, $subject, $message, $headers);
			@$send2 = mail($to2, $subject, $message, $headers);
			
			if($send && $send2) {
				$return['type'] = 'success';
				$return['message'] = 'RSVP successfully sent.';
			} else {
				$return['message'] = 'Error sending RSVP.';
			}
		} else {
			$return['message'] = 'Error validating email.';
		}
	}
	
	die(json_encode($return));
}


if(isset($_POST['get_auto_value'])) {
	$num1 = rand(1, 10);
	$num2 = rand(1, 10);
	$_SESSION['_form_validate'] = 'Wedding City?';
	
	$return = array(
		'data' => encode("In what city is our wedding?"),
		'session' => $_SESSION
	);
	
	die(json_encode($return));
}

?>
