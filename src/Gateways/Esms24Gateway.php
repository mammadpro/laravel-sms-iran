<?php
namespace Mavinoo\LaravelSmsIran\Gateways;

class Esms24Gateway extends GatewayAbstract {

	/**
	 * Esms24Gateway constructor.
	 */
	public function __construct() {

		$this->webService  = config('sms.gateway.esms24.webService');
		$this->username    = config('sms.gateway.esms24.username');
		$this->password    = config('sms.gateway.esms24.password');
		$this->from        = config('sms.gateway.esms24.from');
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
			$result = file_get_contents("{$this->webService}?userName={$this->username}&password={$this->password}&domainName={$this->has_key}&smsText={$msg}&reciverNumber={$number}&senderNumber={$this->from}");
		}

		if($result) {
			return $result;
		}
	}


	/**
	 * @return mixed
	 */
	public function getCredit() {
		if(!$this->username and !$this->password)
			return 'Blank Username && Password';

		return true;
	}

}