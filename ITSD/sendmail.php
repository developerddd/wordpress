<?php
$name = $_REQUEST['name'];
$email = $_REQUEST['email'];
$phone = $_REQUEST['phone'];
$message = $_REQUEST['message'];
$attaches = $_REQUEST['attaches'];
 
if($attaches) {
	$attaches = explode(",",$attaches);
}
$directory_path = getcwd()."/uploads/";

// email fields: to, from, subject, and so on
$to = "contact@itsteady.com";
$from = $email; 
$subject ="Contact from " . $name; 
$message = $message;
$headers = "From: $from";

// boundary 
$semi_rand = md5(time()); 
$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 

// headers for attachment 
$headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 

// multipart boundary 
$message = "This is a multi-part message in MIME format.\n\n" . "--{$mime_boundary}\n" . "Content-Type: text/plain; charset=\"iso-8859-1\"\n" . "Content-Transfer-Encoding: 7bit\n\n" . $message . "\n\n"; 
$message .= "--{$mime_boundary}\n";

// preparing attachments
for($x=0;$x<count($attaches);$x++){
	$file = fopen($directory_path.$attaches[$x],"rb");
	$data = fread($file,filesize($directory_path.$attaches[$x]));
	fclose($file);
	$data = chunk_split(base64_encode($data));
	$message .= "Content-Type: {\"application/octet-stream\"};\n" . " name=\"$attaches[$x]\"\n" . 
	"Content-Disposition: attachment;\n" . " filename=\"$attaches[$x]\"\n" . 
	"Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
	$message .= "--{$mime_boundary}\n";
}

// send

$ok = @mail($to, $subject, $message, $headers); 
if ($ok) { 
	echo "mail sent to $to!"; 
} else { 
	echo "mail could not be sent!"; 
} 
