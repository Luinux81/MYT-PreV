<?php

include_once "./config.php";
include_once "./clase.tool.php";
include_once "./clase.comprador.php";
include_once "./clase.compra.php";

// CONFIG: Enable debug mode. This means we'll log requests into 'ipn.log' in the same directory.
// Especially useful if you encounter network errors or other intermittent problems with IPN (validation).
// Set this to 0 once you go live or don't require logging.
define("DEBUG", 0);

// Set to 0 once you're ready to go live
define("USE_SANDBOX", 0);


define("LOG_FILE", "./ipn.log");


// Read POST data
// reading posted data directly from $_POST causes serialization
// issues with array data in POST. Reading raw POST data from input stream instead.
$raw_post_data = file_get_contents('php://input');
$raw_post_array = explode('&', $raw_post_data);
$myPost = array();
foreach ($raw_post_array as $keyval) {
	$keyval = explode ('=', $keyval);
	if (count($keyval) == 2)
		$myPost[$keyval[0]] = urldecode($keyval[1]);
}
// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';
if(function_exists('get_magic_quotes_gpc')) {
	$get_magic_quotes_exists = true;
}
foreach ($myPost as $key => $value) {
	if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
		$value = urlencode(stripslashes($value));
	} 
	else{
	$value = urlencode($value);
	}
$req .= "&$key=$value";
}

// Post IPN data back to PayPal to validate the IPN data is genuine
// Without this step anyone can fake IPN data

if(USE_SANDBOX == true) {
	$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
} 
else {
	$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
}

$ch = curl_init($paypal_url);
if ($ch == FALSE) {
	return FALSE;
}

curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);

if(DEBUG == true) {
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
}

// CONFIG: Optional proxy configuration
//curl_setopt($ch, CURLOPT_PROXY, $proxy);
//curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);

// Set TCP timeout to 30 seconds
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

// CONFIG: Please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set the directory path
// of the certificate as shown below. Ensure the file is readable by the webserver.
// This is mandatory for some environments.

//$cert = __DIR__ . "./cacert.pem";
//curl_setopt($ch, CURLOPT_CAINFO, $cert);

$res = curl_exec($ch);
if (curl_errno($ch) != 0) // cURL error
{
if(DEBUG == true) {	
error_log(date('[Y-m-d H:i e] '). "Can't connect to PayPal to validate IPN message: " . curl_error($ch) . PHP_EOL, 3, LOG_FILE);
}
curl_close($ch);
exit;

} else {
// Log the entire HTTP response if debug is switched on.
if(DEBUG == true) {
error_log(date('[Y-m-d H:i e] '). "HTTP request of validation request:". curl_getinfo($ch, CURLINFO_HEADER_OUT) ." for IPN payload: $req" . PHP_EOL, 3, LOG_FILE);
error_log(date('[Y-m-d H:i e] '). "HTTP response of validation request: $res" . PHP_EOL, 3, LOG_FILE);

// Split response headers and payload
list($headers, $res) = explode("\r\n\r\n", $res, 2);
}
curl_close($ch);
}

// Inspect IPN validation result and act accordingly

if (strcmp ($res, "VERIFIED") == 0) {
// check whether the payment_status is Completed
// check that txn_id has not been previously processed
// check that receiver_email is your PayPal email
// check that payment_amount/payment_currency are correct
// process payment and mark item as paid.

// assign posted variables to local variables


$first_name=$_POST['first_name'];
$last_name=$_POST['last_name'];
$payer_email = $_POST['payer_email'];

$payment_amount = $_POST['mc_gross'];
$payment_currency = $_POST['mc_currency'];

$payment_status = $_POST['payment_status'];
$receiver_email = $_POST['receiver_email'];

$item_name = $_POST['item_name'];
$item_number = $_POST['item_number'];
$item_id=$_POST['item_id'];
$txn_id = $_POST['txn_id'];
$cantidad=$_POST['quantity'];

}
//Objeto multiusos
$t=new Tool();


//Datos del comprador
$comprador=new Comprador();
$comprador->nombre=str_ireplace("'"," ",$first_name);
$comprador->apellidos=str_ireplace("'"," ",$last_name);
$comprador->email=$payer_email;


//Datos de la compra
$compra=new Compra();
$compra->email_vendedor=$receiver_email;
$compra->email_comprador=$payer_email;
$compra->item=$item_name;
$compra->item_id=$item_number;
$compra->precio=$payment_amount;
$compra->cantidad=$cantidad;
$compra->comprador=$comprador;
$compra->estado=$payment_status;
$compra->id_transaccion=$txn_id;



Tool::log("   ",LOG_FILE);
Tool::log("****  [NUEVA TRANSACCION]  ****",LOG_FILE);
Tool::log($req,LOG_FILE);

$idEvento=$compra->getEvento($item_number);
if(!$idEvento){
    Tool::log("[ERROR] Error de la base de datos obteniendo ID de Evento",LOG_FILE);
}
else{
    $compra->id_evento=$idEvento;
}


$auxReg1=$comprador->estaRegistrado();

if(!$auxReg1){	
	if($comprador->registraComprador()){
			Tool::log("[OK] Registro de nuevo comprador",LOG_FILE);
	}
	else{
		Tool::log("[ERROR] Error de la base de datos registrando nuevo comprador",LOG_FILE);
	}
}
else{
	Tool::log("[OK] Comprador ya registrado ",LOG_FILE);
}


$auxReg2=$compra->compraVerificada();

if($auxReg2){
	Tool::log("[OK] Compra con ID " . $compra->id_transaccion . " verificada.",LOG_FILE);
	
	$auxReg3=$compra->compraRegistrada();
	if(!$auxReg3){
		$res=$compra->registraCompra();
		
		if($res){
			Tool::log("[OK] Compra con ID " . $compra->id_transaccion . " registrada correctamente",LOG_FILE);
			Tool::notificaMAIL(true,$compra);
		}
	}
	else{
		Tool::log("[ERROR] Compra con ID " . $compra->id_transaccion . " duplicada",LOG_FILE);
	}
}
else{
		Tool::log("[ERROR] Compra con ID " . $compra->id_transaccion . " erronea o no completada",LOG_FILE);
}



/*
if(DEBUG == true) {
error_log(date('[Y-m-d H:i e] '). "Verified IPN: $req ". PHP_EOL, 3, LOG_FILE);
error_log($first_name . " " . $last_name . " con e-mail " . $payer_email . " compra " . $item_name . " por " . $payment_amount . " " . $payment_currency . PHP_EOL, 3, LOG_FILE);
}
} else if (strcmp ($res, "INVALID") == 0) {
// log for manual investigation
// Add business logic here which deals with invalid IPN messages
if(DEBUG == true) {
error_log(date('[Y-m-d H:i e] '). "Invalid IPN: $req" . PHP_EOL, 3, LOG_FILE);
}
}
*/
?>