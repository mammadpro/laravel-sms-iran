<?php
namespace Mavinoo\LaravelSmsIran\Gateways;

class BayanegahGateway extends GatewayAbstract {

	/**
	 * BayanegahGateway constructor.
	 */
	public function __construct() {

		$this->webService  = config('sms.gateway.bayanegah.webService');
		$this->username    = config('sms.gateway.bayanegah.username');
		$this->password    = config('sms.gateway.bayanegah.password');
		$this->from        = config('sms.gateway.bayanegah.from');
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
		try {
			$client = new \SoapClient( $this->webService );
			$result = $client->SendSms(
				[
					'username' => $this->username,
					'password' => $this->password,
					'from'     => $this->from,
					'to'       => $numbers,
					'text'     => $text,
					'flash'    => $isflash,
					'udh'      => '',
				]
			);

			return $result;
		} catch( SoapFault $ex ) {
			echo $ex->faultstring;
		}
	}


	/**
	 * @return mixed
	 */
	public function getCredit() {
		if(!$this->username and !$this->password)
			return 'Blank Username && Password';
		try {
			$client = new \SoapClient( $this->webService );

			return $client->Credit( [
				                        "username" => $this->username,
				                        "password" => $this->password,
			                        ] )->CreditResult;
		} catch( SoapFault $ex ) {
			echo $ex->faultstring;
		}
	}

}