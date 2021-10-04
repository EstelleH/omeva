<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 *
 * @package Nicoka Job
 * @since 1.0.0
 */
class NicokaRest {

	/**
	 * The single instance of the class.
	 *
	 * @var self
	 * 
	 */
	private static $_instance = null;

	private $header = [];
	
	private $curlInstance;
	
	private $token = NICOKA_INSTANCE_TOKEN;
	
	/**
	 *
	 * 
	 * @staticw
	 * @return self Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->curlInstance = new WP_Http_Curl; 

	}
	/**
	 * Filtering Custom fields
	 *
	 * @param [type] $dt
	 * @param [type] $filter
	 * @return void
	 */
	private function filterCustomsFields($dt,$filter){
		$data = $dt;
		if ($filter && is_array($filter))
		{
			foreach($data->fields as $key => $data_){
				if(strtolower($data_->{$filter['field']}) !== strtolower($filter['filter'])){
					unset($data->fields[$key]);
				}
				else if(isset($filter['filter_values']))
				{
					foreach($data_->uiPickList as $keyUi => $dataUi)
					{
						if(is_array($filter['filter_values']) && isset($filter['filter_values']['range'])){
							if($filter['filter_values']['range'][0] >= (int)$dataUi->value || $filter['filter_values']['range'][1] <=  (int)$dataUi->value)
							unset($data_->uiPickList[$keyUi]);
						}
						else
						if (!in_array($dataUi->value, $filter['filter_values'])){
							unset($data_->uiPickList[$keyUi]);
						}
					}
				}
				
			}
		}
		if (count($data->fields) > 0){
		$arrayResult = array_values($data->fields);
			return $arrayResult[0];
		}
		else return null;
	}
	/**
	 * Get Custom fields from nicoka Instance
	 *
	 * @param [type] $entitie
	 * @param [type] $filter
	 * @param [type] $order
	 * @return object
	 */
	public function getNicokaCustomFields($entitie, $filter, $order=null){
		if($this->token !== null) {
			$this->header['Authorization'] = 'Bearer '.$this->token;
		}
		
		$url = sprintf(NICOKA_INSTANCE_URL."/api/%s/customFields/" , $entitie);
		
		if($order && is_array($order) )
			$url.'&orderBy='.$order[0].':'.$order[1];

		$request = $this->curlInstance->request($url, array(
			'timeout' 	  => '20',
			'method'      => 'GET',
			'headers'     => $this->header
		));

		if(is_array($request))
		{
			$data = json_decode($request['body']);
			
			if (is_array($filter) && isset($filter['filter']))
				return $this->filterCustomsFields($data, $filter);
			else if (is_array($filter)){
	
				$result = [];

				foreach($filter as $fil){
					$dt = clone $data;
					$result[$fil['filter']] = $this->filterCustomsFields($dt, $fil);
					
				}
				return $result;

			}else{
			 return $data;
			}
		}
	}
	/**
	 * Set parameters to launch Rest Request
	 *
	 * @param varchar $url
	 * @param array $filter
	 * @param array $order
	 * @param integer $limit
	 * @return void
	 */
 	static function setParams(&$url, $filter= null,$order=null, $limit =null ){
		if ($filter){
			if(is_array($filter)){
				foreach((array)$filter as $keyType => $valType){
					if(is_array($valType)){
						foreach ($valType as $dt){
							$request[] = $keyType.'[]'."=".$dt;			
						}
					}
					else 
					$request[] = $keyType.(is_array($valType) ? '[]':'')."=".$valType;		
				}	
				 $url.='&'.implode('&',$request);
			}
		}
		
		if($order && is_array($order) )
		 $url.='&orderBy='.$order[0].':'.$order[1];

		if($limit && intval($limit) > 0 )
			$url.='&limit='.$limit;

	}
	/**
	 * Get values of specific entities
	 *
	 * @param array $filter
	 * @param array $order
	 * @param integer $limit
	 * @return object
	 */
	public function getEntitiesValues($entities, $filter=null, $order=null, $limit=null){

		if(empty($entities)){
			throw new Exception("Veuillez spécifier une entité");
		}

		if($this->token !== null) {
			$this->header['Authorization'] = 'Bearer '.$this->token;
		}

		$url = sprintf(NICOKA_INSTANCE_URL.'/api/application/entities/%s/values/?__hr=1', $entities);

		$this->setParams($url, $filter, $order, $limit);
		
		$request = $this->curlInstance->request($url, array(
			'timeout' 	  => '20',
			'method'      => 'GET',
			'headers'     => $this->header
		));

		if(is_array($request))
		{
			$data = json_decode($request['body']);
			return $data;
		}
	}
	
	/**
	 * Get events from Nicoka Instance
	 *
	 * @param [type] $type
	 * @param [type] $order
	 * @return array
	 */
	public function getNicokaEvents($location, $filter=null, $order=null, $limit=null) {

		if(empty($location)){
			throw new Exception("Veuillez spécifier une location");
		}

		if($this->token !== null) {
			$this->header['Authorization'] = 'Bearer '.$this->token;
		}
		
		$url = sprintf(NICOKA_INSTANCE_URL.'/api/locations/%s/events?__hr=1', $location);
		
		$this->setParams($url, $filter, $order, $limit);
		
		$request = $this->curlInstance->request($url, array(
			'timeout' 	  => '20',
			'method'      => 'GET',
			'headers'     => $this->header
		));

		if(is_array($request))
		{
			$data = json_decode($request['body']);
			return $data->data;
		}
	}

	/**
	 * Get list of jobs
	 *
	 * @param [type] $type
	 * @param [type] $order
	 * @return array
	 */
	public function getNicokaJobs($filter=null, $order=null, $limit=null) {
		if($this->token !== null) {
			$this->header['Authorization'] = 'Bearer '.$this->token;
		}
		
		$url = NICOKA_INSTANCE_URL.'/api/jobs?published='.NICOKA_PUBLISHED_JOB.'&__hr=1&orderBy=published_on:DESC';
		
		$this->setParams($url, $filter, $order, $limit);

		$request = $this->curlInstance->request($url, array(
			'timeout' 	  => '20',
			'method'      => 'GET',
			'headers'     => $this->header
		));

		if(is_array($request))
		{
			$data = json_decode($request['body']);
			return $data->data;
		}
	}

	/**
	 * Get list of contract type
	 *
	 * @return array
	 */
	public function getNicokaContractTypes() {
		if($this->token !== null) {
			$this->header['Authorization'] = 'Bearer '.$this->token;
		}

		$url = NICOKA_INSTANCE_URL.'/api/jobs/contractTypes';

		$request = $this->curlInstance->request($url, array(
			'timeout' 	  => '20',
			'method'      => 'GET',
			'headers'     => $this->header
		));

		if(is_array($request))
		{
			$data = json_decode($request['body']);
			return $data;
		}
	}

	/**
	 * Get specific Job by uid
	 *
	 * @param varchar $id
	 * @return void
	 */
	public function getNicokaJob($id) {

		if($this->token !== null) {
			$this->header['Authorization'] = 'Bearer '.$this->token;
		}
		$url = sprintf(NICOKA_INSTANCE_URL."/api/jobs?uid=%s&__hr=1",$id);

		$request = $this->curlInstance->request($url, array(
			'timeout' 	=> '20',
			'method'    => 'GET',
			'headers'   => $this->header
		));

		if(is_array($request))
		{
			$data = json_decode($request['body']);
			return $data->data[0];
		}
	}

	public function print_error($info){
		return ['type'=> 'error', 'message'=> '<div class="infos error">'.$info.'</div>'];
	}

	public function print_success($info){
		return ['type'=> 'success', 'message'=> '<div class="infos success">'.$info.'</div>'];
	}
	/**
	 * Add candidate to the job
	 *
	 * @param [type] $data
	 * @param [type] $jobid
	 * @param [type] $captcha
	 * @param boolean $protect
	 * @return void
	 */
	public function addCandidateToJob($data, $jobid, $captcha, $protect = true){

		if($protect){
			if (!$this->captchaGoogleVerification($captcha))
				return $this->print_error('Le captcha n\'est pas valide');
		}

		if($this->token !== null) {
			$this->header['Authorization'] = 'Bearer '.$this->token;
		}

		$this->header['Content-Type']= 'application/json';
		
		$url = sprintf(NICOKA_INSTANCE_URL."/api/jobs/%s/apply/",$jobid);

        $dataArray = json_decode($data, true);

        $address = [
            "type" => 6,
            "street" => $dataArray['street'],
            "zipcode" => $dataArray['zipcode'],
            "city" => $dataArray['city'],
            "country" => "FR"
        ];

		$request = $this->curlInstance->request($url, array(
			'timeout' 	=> '60',
			'method'    => 'POST',
			'headers'   => $this->header,
			'body' 		=> $data
		));

		if(is_array($request))
		{
			$data = json_decode($request['body']);
			if(isset($data->error))
				return $this->print_error($data->error);
			else{
                $this->saveAddress($data->candidateid, $address);
                return  $this->print_success($data->success);
            }

		}
	}

	/**
	 * Add free candidate
	 *
	 * @param [type] $data
	 * @param [type] $jobid
	 * @param [type] $captcha
	 * @param boolean $protect
	 * @return void
	 */
	public function addFreeCandidate($data, $captcha, $protect = true){

		if($protect){
			if (!$this->captchaGoogleVerification($captcha))
				return $this->print_error('Le captcha n\'est pas valide');
		}

		if($this->token !== null) {
			$this->header['Authorization'] = 'Bearer '.$this->token;
		}

		$this->header['Content-Type']= 'application/json';
		$url = sprintf(NICOKA_INSTANCE_URL."/api/jobs/unsolicitedApply/");

		$dataArray = json_decode($data, true);

		$address = [
		    "type" => 6,
            "street" => $dataArray['street'],
            "zipcode" => $dataArray['zipcode'],
            "city" => $dataArray['city'],
            "country" => "FR"
        ];

		$request = $this->curlInstance->request($url, array(
			'timeout' 	=> '60',
			'method'    => 'POST',
			'headers'   => $this->header,
			'body' 		=> $data
		));

		if(is_array($request))
		{
			$data = json_decode($request['body']);

			if(isset($data->error))
				return $this->print_error($data->error);
			else{
			    $this->saveAddress($data->candidateid, $address);
                return  $this->print_success($data->success);
            }

		}
	}

    /**
     * Function to save principal candidate address
     *
     * @param $candidateId
     */
	private function saveAddress($candidateId, $address){
        $this->header['Content-Type']= 'application/json';
        $url = sprintf(NICOKA_INSTANCE_URL."/api/candidates/%s/addresses", $candidateId);

        $data = json_encode($address);

        $request = $this->curlInstance->request($url, array(
            'timeout' 	=> '60',
            'method'    => 'POST',
            'headers'   => $this->header,
            'body' 		=> $data
        ));

        if(is_array($request))
        {
            $data = json_decode($request['body']);

            if(isset($data->error))
                return $this->print_error($data->error);
            else{
                return  $this->print_success($data->success);
            }

        }
    }

	/**
	 * Captcha Verification
	 *
	 * @param [type] $responseVerification
	 * @return void
	 */
	private function captchaGoogleVerification($responseVerification){
		
		$params = ['secret' => NICOKA_GOOGLE_CAPTCHA_SECRET, 'response'=> $responseVerification ];


		$request = $this->curlInstance->request('https://www.google.com/recaptcha/api/siteverify'
		, array(
			'timeout' 	=> '20',
			'method'    => 'POST',
			'body' 		=> $params
		));

		if(is_array($request))
		{
			$data = json_decode($request['body']);
			if(isset($data->success))
				return $data->success;
		}
	}

}
NicokaRest::instance();
