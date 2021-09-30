# JSON-SOAP-Proxy

A simple and basic JSON to SOAP proxy implementation in PHP. 

This script can be used as a very simple proxy between a JSON client and a SOAP API. This script is not intended to create a RESTfull API. The main simple functionality is a generic translation of the received JSON request body into a SOAP request. The JSON-SOAP-Proxy script does not have any knowledge about the user SOAP API and only requires the following details with the request.

* The query string containing the SOAP function name
* The request body containing the JSON encoded parameters for the SOAP API

## Configuration

There are two ways of configuring the script. Either the configuration is part of the  script or it can be passed as http headers to the script. 

**ATTENTION:** The possibility to pass the configuration to the script via http headers can pose a security issue. 

The following two configuration options can be found in the script.

* $conf_location
* $conf_namespace

The **conf_location** shall contain the URL to the SOAP API.

The **conf_namespace** shall contain the XML namespace needed for the SOAP request (xmlns).

If both configuration variables are set to "" (empty string), the configuration can be passed via the following HTTP headers.

* X-SOAP-location
* X-SOAP-namespace

## PHP requirements

This script requires the **SOAP** and the **JSON** php modules to be installed. Please refer to the PHP website or the package repository for a description to install the required modules.

* [SOAP](https://www.php.net/manual/en/book.soap.php)
* [JSON](https://www.php.net/manual/de/ref.json.php)

## Examples

To trigger a request using curl, the following command can be used.

    curl http://127.0.0.1/?soapFunctionName \
    	-H "X-SOAP-location: http://127.0.0.1:8081/soap" \
    	-H "X-SOAP-namespace: http://127.0.0.1:8082/NicToolServer/SOAP" \
    	-H "Content-Type: application/json" \
    	-d '{"soap_field": "value", "soap_field2": 1234}'

If the SOAP location and namespace is configured in the script, the two related headers can be omitted.

## Error handling

If no request body is shown, use the "-v" option of curl to show the headers. The JSON-SOAP proxy script ujses http headers and the return code to indicate errors. The following example shows an error in the JSON-SOAP proxy.

    < HTTP/1.1 400 Bad Request
    < Date: Wed, 29 Sep 2021 09:15:08 GMT
    < Server: Apache/2.4.37 (Red Hat Enterprise Linux) OpenSSL/1.1.1g
    < X-Powered-By: PHP/7.2.24
    < X-Error-Reason: JSON missing or not parseable
    < Content-Length: 0
    < Connection: close
    < Content-Type: text/html; charset=UTF-8

The return code 400 indicates the error of the JSON-SOAP proxy script itself. The http header "X-Error-Reason" shows a description of the error.
