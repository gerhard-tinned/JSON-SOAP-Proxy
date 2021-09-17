<?php
#
# JSON-SOAP-Proxy
#
# This script can be used as a very simple proxy between a JSON client and a 
# SOAP API. This script is not intended to create a RESTfull API.
#
# @author     https://github.com/gerhard-tinned
# @copyright  2021
# @link       https://github.com/gerhard-tinned/JSON-SOAP-Proxy
# @license    https://opensource.org/licenses/BSD-2-Clause   BSD 2-Clause License
# @version    0.2.0
#

// BEGIN CONFIGURATION
$conf_location = "http://127.0.0.1:8081/soap";
$conf_namespace = "http://127.0.0.1:8081/NAMESPACE/SOAP";
// END CONFIGURATION

// Reding the the Inpurt details
$request_json = @file_get_contents('php://input');
$request_name = $_SERVER['QUERY_STRING'];

// Parse JSON input
$request_body = @json_decode($request_json, true);
if($request_body == "" || $request_name == "") {
	// if there is no decoded json body, return 400
    header("X-Error-Reason: JSON missing or not parseable");
   	http_response_code(400);
	exit;
}

// if the configuration items are empty, the configfuration is read from the http header
if ($conf_location == "" && $conf_namespace == "") {
	if (isset($_SERVER['HTTP_X_SOAP_LOCATION'])) {
		$conf_location = $_SERVER['HTTP_X_SOAP_LOCATION'];
	}
	if (isset($_SERVER['HTTP_X_SOAP_NAMESPACE'])) {
		$conf_namespace = $_SERVER['HTTP_X_SOAP_NAMESPACE'];
	}
}
if($conf_location == "" || $conf_namespace == "") {
	// when the location and namespace is empty, 400 is returned
	header("X-Error-Reason: Config missing or incomplete");
	http_response_code(500);
	exit;
}

// Create the SoapClient instance
$client     = new SoapClient(null, array("location" => $conf_location, "uri" => $conf_namespace, "trace" => true, "exception" => true));

// Call wsdl function
$result = $client->__soapCall($request_name, array($request_body), NULL);

// Output the response as json
header("Content-Type: application/json");
echo json_encode($result, JSON_PRETTY_PRINT)."\n";

?>
