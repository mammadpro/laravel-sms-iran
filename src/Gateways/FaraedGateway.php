<?php
namespace Mavinoo\LaravelSmsIran\Gateways;

class FaraedGateway extends GatewayAbstract {

	/**
	 * FaraedGateway constructor.
	 */
	public function __construct() {

		$this->webService  = config('sms.gateway.faraed.webService');
		$this->username    = config('sms.gateway.faraed.username');
		$this->password    = config('sms.gateway.faraed.password');
		$this->from        = config('sms.gateway.faraed.from');
	}


	/**
	 * @param array $numbers
	 * @param       $text
	 * @param bool  $isflash
	 *
	 * @return mixed
	 * @internal param $to | array
	 */
	public function sendSMS( array $numbers, $text, $isflash = false ) {
		// Check credit for the gateway
		if(!$this->GetCredit()) return;

		$msg    = urlencode($text);
		$result = null;
		foreach($numbers as $number) {
			$result = file_get_contents("{$this->webService}?user={$this->username}&pass={$this->password}&lineNo={$this->from}&to={$number}&text={$msg}");
		}

		if ($result == 'ok') {
			return $result;
		}
	}


	/**
	 * @return mixed
	 */
	public function getCredit() {
		if(!$this->username and !$this->password)
			return 'Blank Username && Password';

		return 1;
	}

}