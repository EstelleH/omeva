<?php

/**
 * @package Nicoka Job
 * @version 1.0.0
 */

/*
Plugin Name: Nicoka Job
Plugin URI: https://www.nicoka.com
Description: Nicoka Plugin
Author: Orinea
Version: 1.0
Author URI: http://www.nicoka.com
*/

/**
 * Class to generate Form
 *
 * @package Nicoka Job
 * @since 1.0.0
 */
class NicokaJob
{

	private static $_instance = null;

	const weekDays = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
	const FileType = ['.doc','.docx,application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document','.pdf'];
	private $job = null;
	
	/**
	 * Allows for accessing single instance of class. Class should only be constructed once per call.
	 *
	 * @since  1.0.0
	 * @static
	 * @return self Main instance.
	 */
	public static function instance()
	{
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

    /**
	 * Constructor.
	 */
	public function __construct()
	{
	
		//--------------------------
		define('NICOKA_ENTITY_CV', 10);
		define('NICOKA_ENTITY_LETTER_MOTIV', 997);

		define('OMEVA_PAGE_POST_URL', 'poste');
		// Add notification when the form is post (refer: Nicoka Doc API);
		define('NICOKA_NOTIFY', true);
		//---------------------------

		define('NICOKA_CONFIDENTIAL_POLITIC', get_option('nicoka_form_url_confidential_politic'));
		define('NICOKA_CONDITION_UTILISATION', get_option('nicoka_form_url_condition_utilisation'));
		define('NICOKA_INDEX_PAGE_JOB', get_option('nicoka_index_page_job'));

		define('NICOKA_GOOGLE_CAPTCHA_SECRET', get_option('nicoka_job_captcha_secret_key'));
		define('NICOKA_GOOGLE_CAPTCHA_CODE', get_option('nicoka_job_captcha_site_key'));

		define('NICOKA_INSTANCE_URL',   get_option('nicoka_job_url_api'));
		define('NICOKA_INSTANCE_TOKEN', get_option('nicoka_job_token_api'));

		define('NICOKA_PUBLISHED_JOB', get_option('nicoka_published_job'));

		//-------------------------------------

		define('NICOKA_JOB_VERSION', '1.0.0');
		define('NICOKA_JOB_WP_VERSION', '4.7.0');

		define('NICOKA_JOB_PLUGIN_DIR', untrailingslashit(plugin_dir_path(__FILE__)));
		define('NICOKA_JOB_PLUGIN_URL', untrailingslashit(plugins_url(basename(plugin_dir_path(__FILE__)), basename(__FILE__))));
		define('NICOKA_JOB_PLUGIN_BASENAME', plugin_basename(__FILE__));

		include_once NICOKA_JOB_PLUGIN_DIR . '/includes/rest.php';
		include_once NICOKA_JOB_PLUGIN_DIR . '/includes/form.php';
		include_once NICOKA_JOB_PLUGIN_DIR . '/includes/template.php';

		if (is_admin()) {
			include_once NICOKA_JOB_PLUGIN_DIR . '/includes/admin/admin.php';
		}
			// Ajax request for both visitor not logged and user logged.
		add_action('wp_ajax_getEvents', array($this, 'getEvents'));

		add_action('wp_ajax_nopriv_getEvents', array($this, 'getEvents'));

		if (!is_admin()) {
			add_action('init', array($this, 'add_rewrite_rules'));

			//Listing of Jobs with filters
			add_shortcode('nicoka-jobs-listing', array($this, 'outputJobsListing'));
			//Job page with the form 
			add_shortcode('nicoka-job', array($this, 'outputJob'));
            //Teaser of Jobs
            add_shortcode('nicoka-jobs-teaser', array($this, 'outputJobsTeaser'));
            //Free Candidate Form
            add_shortcode('nicoka-form-free-candidate', array($this, 'outputFormFreeCandidate'));
		}

        //change title and made request.
        add_filter('wpseo_title', array($this,'filterTitle'), 20);
        add_filter('wpseo_opengraph_title', array($this,'filterTitle'), 20);
        add_filter('wpseo_opengraph_url', array($this,'filter_wpseo_opengraph_url'), 10);
        add_filter( 'wpseo_schema_webpage', array($this,'filter_wpseo_schema_webpage') );
    }

    /**
     * Changes @type of Webpage Schema data.
     *
     * @param array $data Schema.org Webpage data array.
     *
     * @return array Schema.org Webpage data array.
     */
    function filter_wpseo_schema_webpage( $data ) {
        if ( ! is_page( 'poste' ) ) {
            return $data;
        }

        global $wp;
        global $wp_query;

        if(isset($wp_query->query_vars['jobid']) && !empty($wp_query->query_vars['jobid'])){
            $rest = NicokaRest::instance();
            $dataNicoka = $rest->getNicokaJob($wp_query->query_vars['jobid']);
            $this->job = $dataNicoka;
            $data['name'] = $this->job->label . " - Omeva le recrutement engagé";
        }

        $data['url'] = home_url( $wp->request );
        $data['@id'] = home_url( $wp->request ) . "#webpage";

        return $data;
    }

    // define the wpseo_opengraph_url callback
    function filter_wpseo_opengraph_url( $wpseo_frontend ) {
        global $wp;

        if(isset($wp->query_vars['jobid']) && !empty($wp->query_vars['jobid'])){
            $wpseo_frontend = home_url( $wp->request );
        }

        return $wpseo_frontend;
    }

    /**
	 * Add rewrite rules to show Job page
	 *
	 * @return void
	 */
	function add_rewrite_rules()
	{
        global $wp_rewrite;

        add_rewrite_tag('%jobid%', '([^&]+)');
        add_rewrite_rule(OMEVA_PAGE_POST_URL."/([^/]+)__([^/]+)", 'index.php?pagename='.OMEVA_PAGE_POST_URL.'&title=$matches[1]&jobid=$matches[2]', "top");
        $wp_rewrite->flush_rules();
	}

	/**
	 * Added specific CSS and JS scripts file depend of type 
	 *
	 * @param [type] $type
	 * @return void
	 */
	function add_css(string $type)
	{
		global $wp;
		wp_register_style('nicoka_css_form', plugins_url('assets/css/style_form.css', __FILE__));
		wp_enqueue_style('nicoka_css_form');
		wp_register_script('nicoka_js_google', 'https://www.google.com/recaptcha/api.js');
		wp_enqueue_script('nicoka_js_google');
		switch ($type) {
			case 'listJobs':
                wp_register_script('nicoka_job', NICOKA_JOB_PLUGIN_URL . '/assets/js/list_jobs.js', array('jquery'));
                wp_enqueue_script('nicoka_job');
                wp_register_style('nicoka_css_job', NICOKA_JOB_PLUGIN_URL . '/assets/css/style_list_jobs.css');
				wp_enqueue_style('nicoka_css_job');
				break;
			case 'teaserJobs':
				wp_register_style('nicoka_css_job', NICOKA_JOB_PLUGIN_URL . '/assets/css/style_list_jobs.css');
				wp_register_style('nicoka_css_job_teaser', NICOKA_JOB_PLUGIN_URL . '/assets/css/style_teaser_job.css');
				wp_enqueue_style('nicoka_css_job');
				wp_enqueue_style('nicoka_css_job_teaser');
				break;
			case 'job':
				wp_register_script('nicoka_job', NICOKA_JOB_PLUGIN_URL . '/assets/js/job.js', array('jquery'));
				wp_enqueue_script('nicoka_job');
				wp_register_style('nicoka_css_job', NICOKA_JOB_PLUGIN_URL . '/assets/css/style_job.css');
				wp_enqueue_style('nicoka_css_job');
                wp_register_script('nicoka_job_share_this', 'https://platform-api.sharethis.com/js/sharethis.js#property=5fa80d35e3f5df0012a01854&product=inline-share-buttons');
                wp_enqueue_script('nicoka_job_share_this');
				break;
            case 'freeCandidateForm':
                wp_register_script('nicoka_free_candidate', NICOKA_JOB_PLUGIN_URL . '/assets/js/free-candidate.js', array('jquery'));
                wp_enqueue_script('nicoka_free_candidate');
                wp_register_style('nicoka_css_free_candidate', NICOKA_JOB_PLUGIN_URL . '/assets/css/style_form.css');
                wp_enqueue_style('nicoka_css_free_candidate');
                break;
		}
	}

	/**
	 * Output Listing Job
	 *
	 * @param Array $opt
	 * @return string
	 */
	public function outputJobsListing($opt)
	{
		global $post;
		$rest = NicokaRest::instance();

		$order = (isset($opt['order'])) ? $opt['order'] :  null;
        $jobCategoriesList = $rest->getNicokaJobCategories();
        //var_dump($jobCategoriesList); exit;
        $jobs = $rest->getNicokaJobs(null, $order, '400');
        $types = [];
        $departments = [];
        $jobCategories = [];

        foreach($jobs as $job){
            if (!array_search($job->contract_type, $types)){
                $types[$job->contrat_type] = $job->contract_type__formated;
            }

            if (!array_search($job->city, $departments)){
                $departments[$job->city] = $job->city;
            }

            if (array_key_exists($job->categoryid, $jobCategoriesList)){
                $jobCategories[$job->categoryid] = $jobCategoriesList[$job->categoryid];
            }
        }

		if (intval($opt['limit']) > 0){
            $jobs = array_slice($jobs, 0, intval($opt['limit']));
        }

		ob_start();

        $this->add_css('listJobs');

        echo NicokaTemplate::instance()->getTemplateJobs($jobs, $types, $departments, $jobCategories);
        return '<div class="jobs-listing">' . ob_get_clean() . '</div>';
	}

	/**
	 * Output Teaser jobs
	 *
	 * @param Array $opt
	 * @return string
	 */
	public function outputJobsTeaser($opt)
	{
		global $post;
		$rest = NicokaRest::instance();

        $jobs = $rest->getNicokaJobs(null,null,3);

		ob_start();

        $this->add_css('teaserJobs');
        echo NicokaTemplate::instance()->getTemplateTeaserJobs($jobs);
        return '<div class="jobs-listing animated fadeIn">' . ob_get_clean() . '</div>';
	}

	/**
	 * Get List of Events 
	 *
	 * @param Array $opt
	 * @return Json
	 */
	public function getEvents($opt)
	{
		if(isset($_POST['loc']) || isset($opt['loc']))
			$loc = (wp_doing_ajax()) ? $_POST['loc'] : $opt['loc'];
		if(isset($_POST['order']) || isset($opt['order']))
			$order = (wp_doing_ajax()) ? $_POST['order'] : $opt['order'];
		$rest = NicokaRest::instance();
		$date = new \DateTime();
		$endDate = (clone $date)->add(new \DateInterval('P3M'));

		//$order = (isset($opt['order'])) ? $opt['order'] : null;

		$filter = ['start'=>''.$date->format("Y-m-d").','.$endDate->format("Y-m-d").'', 'notFull'=> 1 ];

		$data = $rest->getNicokaEvents($loc, $filter, $order, '200');
		$result = [];
		foreach ($data as $val) {
			$date = date("d-m-Y - G:i", strtotime($val->start));
			if (wp_doing_ajax()) {	
			 	$result[] =['id' => $val->id, 'date' => self::weekDays[date("w", strtotime($val->start))] . " " . $date];
			} else {
				 $result[$val->id] = (self::weekDays[date("w", strtotime($val->start))] . " " . $date);
			 }
		};
		return (wp_doing_ajax()) ? wp_send_json($result) : $result;
	}

	/**
	 * Get form Data
	 * @param boolean $salon
	 * @return Array
	 */
	private function processDataForm()
	{
		$data = filter_input_array(INPUT_POST);
		$data = array_filter($data, function ($elem) { return !($elem == ''); });

		// Consent
        $data['dataUsageConsent'] = $data['privacyPolicyConsent'];

		if (NICOKA_NOTIFY) $data['notify'] = 1;

		$arrayType = [
			'application/msword',
			'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
			'application/pdf'
		];

		foreach ($_FILES as $file => $param) {
			if (!empty($param['tmp_name'])) {
				$dataFile = file_get_contents($param['tmp_name']);
				if (!(in_array($param['type'], $arrayType))) {
					if($file == NICOKA_ENTITY_CV)
						throw new Exception("Format de fichier incorrect pour le CV");
					else if($file == NICOKA_ENTITY_LETTER_MOTIV)
						throw new Exception("Format de fichier incorrect pour la lettre de motivation");
					else 
						throw new Exception("Format de fichier incorrect");
				}
				if (($file == NICOKA_ENTITY_CV || $file == NICOKA_ENTITY_LETTER_MOTIV) && in_array($param['type'], $arrayType))
					$data['documents'][] = ['base64content' => base64_encode($dataFile), 'filename' => $param['name'], 'doctype' => $file];
			}
		}
		return $data;
	}

	/**
	 * Change title of Web page
	 * 
	 * Here, when is a job page we made the request with Curl to get the job Data and set title with the name of the Job, we store job 
	 *
	 * @param String $title
	 * @return string
	 */
	public function filterTitle($title) {

		global $wp_query;
	
		if(isset($wp_query->query_vars['jobid']) && !empty($wp_query->query_vars['jobid'])){
			$rest = NicokaRest::instance();
			$data = $rest->getNicokaJob($wp_query->query_vars['jobid']);
			$this->job = $data;	
			$title = $this->job->label . " - Omeva le recrutement engagé";
		}

		return $title;
	}

	/**
	 * Create Form job to candidate on job
	 * 
	 * Show form or post form
	 *
	 * @param integer $jobId
	 * @return void
	 */
	public function jobForm($jobId)
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST' && !is_admin()) {
			try{
				$data = $this->processDataForm();
				$data['additionnalJobs'] = [];

			}catch (Exception $except){
				echo '<div class="job-profil"><div class="infos error">'.$except->getMessage().'</div></div>';
			}
			$captcha = $data["g-recaptcha-response"];
			unset($data["g-recaptcha-response"]);
			$rest =  NicokaRest::instance();

			$response = $rest->addCandidateToJob(JSON_ENCODE($data), $jobId, $captcha);
			echo $response['message'];
		}
		global $wp;
		$formInstance =  NicokaForm::instance();
		?>
		<div class='job-form <?php echo ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($response['type']) && $response['type'] === 'error') ? 'show' : null; ?>'>
			<p class="description">Pour postuler à cette offre, veuillez remplir le formulaire ci-dessous.</p>
			<form method="post" enctype="multipart/form-data" action="<?php echo home_url($wp->request); ?>">
				<?php
                $configFormInfos1 = [
                    [
                        'type'		=> 'select',
                        'required' 	=> true,
                        'placeholder' => 'Civilité *',
                        'option'	=> ['name' 	=> 'civility', 'options' => ['M.', 'Mme'], 'label_hidden' => true],
                        'value' => (($_SERVER['REQUEST_METHOD'] === 'POST' && isset($response['type']) && $response['type'] === 'error' && $_POST['civility']) ? $_POST['civility'] : null)
                    ]
                ];
				$configFormInfos2 = [
                    [
                        'type'		=> 'text',
                        'required' 	=> true,
                        'option'	=> ['name' => 'first_name', 'label_hidden' => true],
                        'placeholder' => 'Prénom *',
                        'value' => (($_SERVER['REQUEST_METHOD'] === 'POST' && isset($response['type']) && $response['type'] === 'error' && $_POST['first_name']) ? $_POST['first_name'] : null)
                    ],
                    [
                        'type'		=> 'text',
                        'required' 	=> true,
                        'option'	=> ['name' => 'last_name', 'label_hidden' => true],
                        'placeholder' => 'Nom *',
                        'value' => (($_SERVER['REQUEST_METHOD'] === 'POST'  && isset($response['type']) && $response['type'] === 'error' && $_POST['last_name']) ? $_POST['last_name'] : null)
                    ]
				];

				$configFormInfos3 = [
                    [
                        'type'			=> 'text',
                        'required' 		=> true,
                        'option'		=> ['name' => 'email', 'label_hidden' => true],
                        'placeholder' 	=> 'E-mail *',
                        'value' 		=> (($_SERVER['REQUEST_METHOD'] === 'POST' && isset($response['type']) && $response['type'] === 'error' && $_POST['email']) ? $_POST['email'] : null)
                    ],
					[
						'type'		=> 'text',
						'required' 	=> true,
						'option'	=> ['name' => 'phone1', 'label_hidden' => true],
						'placeholder' => 'Téléphone *',
						'value' => (($_SERVER['REQUEST_METHOD'] === 'POST' && isset($response['type']) && $response['type'] === 'error' && $_POST['phone1']) ? $_POST['phone1'] : null)
					]
				];

                $configFormAddress1 = [
                    [
                        'type'		=> 'text',
                        'required' 	=> true,
                        'option'	=> ['name' => 'street', 'label_hidden' => true],
                        'placeholder' => 'Adresse *',
                        'value' => (($_SERVER['REQUEST_METHOD'] === 'POST' && isset($response['type']) && $response['type'] === 'error' && $_POST['street']) ? $_POST['street'] : null)
                    ]
                ];
                $configFormAddress2 = [
                    [
                        'type'		=> 'text',
                        'required' 	=> true,
                        'option'	=> ['name' => 'zipcode', 'label_hidden' => true],
                        'placeholder' => 'Code postal *',
                        'value' => (($_SERVER['REQUEST_METHOD'] === 'POST' && isset($response['type']) && $response['type'] === 'error' && $_POST['zipcode']) ? $_POST['zipcode'] : null)
                    ],
                    [
                        'type'		=> 'text',
                        'required' 	=> true,
                        'option'	=> ['name' => 'city', 'label_hidden' => true],
                        'placeholder' => 'Ville *',
                        'value' => (($_SERVER['REQUEST_METHOD'] === 'POST' && isset($response['type']) && $response['type'] === 'error' && $_POST['city']) ? $_POST['city'] : null)
                    ]
                ];

				$configFormDocs = [
					[
						'type'			=> 'file',
						'required' 		=> true,
						'option'		=> ['name' => NICOKA_ENTITY_CV],
						'placeholder' 	=> 'Mon CV *',
						'attributes' 	=> ['accept="'.implode(',',self::FileType).'"'],
						'value' 		=> (($_SERVER['REQUEST_METHOD'] === 'POST' && isset($response['type']) && $response['type'] === 'error' && $_FILES['NICOKA_ENTITY_CV']) ? $_FILES['NICOKA_ENTITY_CV'] : null)
					],
					[
						'type'			=> 'file',
						'required' 		=> false,
						'option'		=> ['name' => NICOKA_ENTITY_LETTER_MOTIV],
						'placeholder' 	=> 'Ma lettre de motivation (optionnel)',
						'attributes' 	=> ['accept="'.implode(',',self::FileType).'"'],
						'value' 		=> (($_SERVER['REQUEST_METHOD'] === 'POST' && isset($response['type']) && $response['type'] === 'error' && $_FILES['NICOKA_ENTITY_LETTER_MOTIV']) ? $_FILES['NICOKA_ENTITY_LETTER_MOTIV'] : null)
					],
                    [
                        'type'		=> 'textarea',
                        'required' 	=> false,
                        'option'	=> ['name' => 'summary_comments'],
                        'placeholder' => 'Commentaires (optionnel)',
                        'value' => (($_SERVER['REQUEST_METHOD'] === 'POST' && isset($response['type']) && $response['type'] === 'error' && $_POST['summary_comments']) ? $_POST['summary_comments'] : null)
                    ],
					[
						'type'		=> 'checkbox',
						'required' 	=> true,
						'option'	=> ['name' => 'privacyPolicyConsent', 'cb_label' => "<span>En cochant cette case, vous reconnaissez avoir pris connaissance et acceptez sans réserve les <a href='".get_page_link(NICOKA_CONDITION_UTILISATION)."'>Conditions Générales d’Utilisation</a> ainsi que la <a href='".get_page_link(NICOKA_CONFIDENTIAL_POLITIC)."'>Politique de Gestion des Données Personnelles</a></span> *", 'desc' => "Les informations personnelles que vous nous avez transmises seront exploitées dans le seul but d’étudier votre candidature. Conformément au règlement général sur la protection des données (RGPD 2016/679) vous disposez d’un droit d’accès, de modification, de rectification, de suppression des données vous concernant."],
						'value' 	=> (($_SERVER['REQUEST_METHOD'] === 'POST' && isset($response['type']) && $response['type'] === 'error' && $_POST['privacyPolicyConsent']) ? $_POST['privacyPolicyConsent'] : null)
					],
					[
						'type'		=> 'hidden',
						'option'	=> ['name' => 'jobid', 'value' => $jobId]
					],
				];

				?><div class="form-content">
					<h3>Mes informations personnelles</h3>
				</div><div class="omeva-form-infos"><?php
						$formInstance->outputForm($configFormInfos1);
						?></div>
                    <div class="omeva-form-infos"><?php
                    $formInstance->outputForm($configFormInfos2);
                    ?></div>
                    <div class="omeva-form-infos"><?php
                    $formInstance->outputForm($configFormInfos3);
                    ?></div>
                <div class="form-content">
                    <h3>Mon adresse</h3>
                </div><?php
                        $formInstance->outputForm($configFormAddress1);
                        ?>
                        <div class="omeva-address"><?php
                        $formInstance->outputForm($configFormAddress2);
                        ?></div>
                <div class="form-content">
					<h3>Mes documents</h3>
				</div><?php
						$formInstance->outputForm($configFormDocs);
						?>
                <fieldset>
                    <p class="description">Les champs contenant le signe * sont obligatoires.</p>
                </fieldset>
				<fieldset>
					<div class="g-recaptcha" data-sitekey="<?php echo NICOKA_GOOGLE_CAPTCHA_CODE; ?>"></div>
				</fieldset>
				<fieldset>
                    <div id="submit-infos">
                        <p class="error-email">Veuillez saisir une adresse email correcte.</p>
                        <p class="error-phone">Veuillez saisir un numéro de téléphone correct.<br/>Format 10 chiffres sans espace ni indicatif.</p>
                    </div>
                    <button id="form-submit" type="submit" class="button">Envoyer</button>
				</fieldset>
			</form>
		</div>
	<?php
	}

    /**
     * Create Free Candidate Form
     *
     * @return void
     */
    public function freeCandidateForm()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !is_admin()) {
            try{
                $data = $this->processDataForm();
                $data['additionnalJobs'] = [];

            }catch (Exception $except){
                echo '<div class="infos error">'.$except->getMessage().'</div>';
            }
            $captcha = $data["g-recaptcha-response"];
            unset($data["g-recaptcha-response"]);
            $rest =  NicokaRest::instance();

            $response = $rest->addFreeCandidate(JSON_ENCODE($data), $captcha);
            echo $response['message'];
        }
        global $wp;
        $formInstance =  NicokaForm::instance();
        ?>
        <div class='free-candidate-form <?php echo ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($response['type']) && $response['type'] === 'error') ? 'show' : null; ?>'>
            <form method="post" enctype="multipart/form-data" action="<?php echo home_url($wp->request); ?>">
                <?php
                $configFormInfos1 = [
                    [
                        'type'		=> 'select',
                        'required' 	=> true,
                        'placeholder' => 'Civilité *',
                        'option'	=> ['name' 	=> 'civility', 'options' => ['M.', 'Mme'], 'label_hidden' => true],
                        'value' => (($_SERVER['REQUEST_METHOD'] === 'POST' && isset($response['type']) && $response['type'] === 'error' && $_POST['civility']) ? $_POST['civility'] : null)
                    ]
                ];
                $configFormInfos2 = [
                    [
                        'type'		=> 'text',
                        'required' 	=> true,
                        'option'	=> ['name' => 'first_name', 'label_hidden' => true],
                        'placeholder' => 'Prénom *',
                        'value' => (($_SERVER['REQUEST_METHOD'] === 'POST' && isset($response['type']) && $response['type'] === 'error' && $_POST['first_name']) ? $_POST['first_name'] : null)
                    ],
                    [
                        'type'		=> 'text',
                        'required' 	=> true,
                        'option'	=> ['name' => 'last_name', 'label_hidden' => true],
                        'placeholder' => 'Nom *',
                        'value' => (($_SERVER['REQUEST_METHOD'] === 'POST'  && isset($response['type']) && $response['type'] === 'error' && $_POST['last_name']) ? $_POST['last_name'] : null)
                    ]
                ];

                $configFormInfos3 = [
                    [
                        'type'			=> 'text',
                        'required' 		=> true,
                        'option'		=> ['name' => 'email', 'label_hidden' => true],
                        'placeholder' 	=> 'E-mail *',
                        'value' 		=> (($_SERVER['REQUEST_METHOD'] === 'POST' && isset($response['type']) && $response['type'] === 'error' && $_POST['email']) ? $_POST['email'] : null)
                    ],
                    [
                        'type'		=> 'text',
                        'required' 	=> true,
                        'option'	=> ['name' => 'phone1', 'label_hidden' => true, 'desc' => 'Format : 0102030405'],
                        'placeholder' => 'Téléphone *',
                        'value' => (($_SERVER['REQUEST_METHOD'] === 'POST' && isset($response['type']) && $response['type'] === 'error' && $_POST['phone1']) ? $_POST['phone1'] : null)
                    ]
                ];

                $configFormAddress1 = [
                    [
                        'type'		=> 'text',
                        'required' 	=> true,
                        'option'	=> ['name' => 'street', 'label_hidden' => true],
                        'placeholder' => 'Adresse *',
                        'value' => (($_SERVER['REQUEST_METHOD'] === 'POST' && isset($response['type']) && $response['type'] === 'error' && $_POST['street']) ? $_POST['street'] : null)
                    ]
                ];
                $configFormAddress2 = [
                    [
                        'type'		=> 'text',
                        'required' 	=> true,
                        'option'	=> ['name' => 'zipcode', 'label_hidden' => true],
                        'placeholder' => 'Code postal *',
                        'value' => (($_SERVER['REQUEST_METHOD'] === 'POST' && isset($response['type']) && $response['type'] === 'error' && $_POST['zipcode']) ? $_POST['zipcode'] : null)
                    ],
                    [
                        'type'		=> 'text',
                        'required' 	=> true,
                        'option'	=> ['name' => 'city', 'label_hidden' => true],
                        'placeholder' => 'Ville *',
                        'value' => (($_SERVER['REQUEST_METHOD'] === 'POST' && isset($response['type']) && $response['type'] === 'error' && $_POST['city']) ? $_POST['city'] : null)
                    ]
                ];

                $configFormDocs = [
                    [
                        'type'			=> 'file',
                        'required' 		=> true,
                        'option'		=> ['name' => NICOKA_ENTITY_CV],
                        'placeholder' 	=> 'Mon CV *',
                        'attributes' 	=> ['accept="'.implode(',',self::FileType).'"'],
                        'value' 		=> (($_SERVER['REQUEST_METHOD'] === 'POST' && isset($response['type']) && $response['type'] === 'error' && $_FILES['NICOKA_ENTITY_CV']) ? $_FILES['NICOKA_ENTITY_CV'] : null)
                    ],
                    [
                        'type'			=> 'file',
                        'required' 		=> false,
                        'option'		=> ['name' => NICOKA_ENTITY_LETTER_MOTIV],
                        'placeholder' 	=> 'Ma lettre de motivation (optionnel)',
                        'attributes' 	=> ['accept="'.implode(',',self::FileType).'"'],
                        'value' 		=> (($_SERVER['REQUEST_METHOD'] === 'POST' && isset($response['type']) && $response['type'] === 'error' && $_FILES['NICOKA_ENTITY_LETTER_MOTIV']) ? $_FILES['NICOKA_ENTITY_LETTER_MOTIV'] : null)
                    ],
                    [
                        'type'		=> 'textarea',
                        'required' 	=> false,
                        'option'	=> ['name' => 'summary_comments'],
                        'placeholder' => 'Commentaires (optionnel)',
                        'value' => (($_SERVER['REQUEST_METHOD'] === 'POST' && isset($response['type']) && $response['type'] === 'error' && $_POST['summary_comments']) ? $_POST['summary_comments'] : null)
                    ],
                    [
                        'type'		=> 'checkbox',
                        'required' 	=> true,
                        'option'	=> ['name' => 'privacyPolicyConsent', 'cb_label' => "<span>En cochant cette case, vous reconnaissez avoir pris connaissance et acceptez sans réserve les <a href='".get_page_link(NICOKA_CONDITION_UTILISATION)."'>Conditions Générales d’Utilisation</a> ainsi que la <a href='".get_page_link(NICOKA_CONFIDENTIAL_POLITIC)."'>Politique de Gestion des Données Personnelles</a></span> *", 'desc' => "Les informations personnelles que vous nous avez transmises seront exploitées dans le seul but d’étudier votre candidature. Conformément au règlement général sur la protection des données (RGPD 2016/679) vous disposez d’un droit d’accès, de modification, de rectification, de suppression des données vous concernant."],
                        'value' 	=> (($_SERVER['REQUEST_METHOD'] === 'POST' && isset($response['type']) && $response['type'] === 'error' && $_POST['privacyPolicyConsent']) ? $_POST['privacyPolicyConsent'] : null)
                    ]
                ];

                ?><div class="form-content">
                    <h3>Mes informations personnelles</h3>
                </div><div class="omeva-form-infos"><?php
                    $formInstance->outputForm($configFormInfos1);
                    ?></div>
                <div class="omeva-form-infos"><?php
                    $formInstance->outputForm($configFormInfos2);
                    ?></div>
                <div class="omeva-form-infos"><?php
                    $formInstance->outputForm($configFormInfos3);
                    ?></div>
                <div class="form-content">
                    <h3>Mon adresse</h3>
                </div><?php
                $formInstance->outputForm($configFormAddress1);
                ?>
                <div class="omeva-address"><?php
                    $formInstance->outputForm($configFormAddress2);
                    ?></div>
                <div class="form-content">
                    <h3>Mes documents</h3>
                </div><?php
                $formInstance->outputForm($configFormDocs);
                ?>
                <fieldset>
                    <p class="description">Les champs contenant le signe * sont obligatoires.</p>
                </fieldset>
                <fieldset>
                    <div class="g-recaptcha" data-sitekey="<?php echo NICOKA_GOOGLE_CAPTCHA_CODE; ?>"></div>
                </fieldset>
                <fieldset>
                    <div id="submit-infos">
                        <p class="error-email">Veuillez saisir une adresse email correcte.</p>
                        <p class="error-phone">Veuillez saisir un numéro de téléphone correct.<br/>Format 10 chiffres sans espace ni indicatif.</p>
                    </div>
                    <button id="form-submit" type="submit" class="button">Envoyer</button>
                </fieldset>
            </form>
        </div>
        <?php
    }

    /**
     * Output specific Job
     *
     * @param Array $opt
     * @return string
     */
    public function outputJob($opt)
    {
        global $wp_query;

        $jobid = $wp_query->query_vars['jobid'];

        if(empty($this->job)){
            $rest = NicokaRest::instance();
            $data = $rest->getNicokaJob($jobid);
            $this->job = $data;
        }

        ob_start();

        $this->add_css('job');
        $template = NicokaTemplate::instance()->getTemplateJob($this->job);
        $template .= $this->jobForm($this->job->jobid);
        $template .= NicokaTemplate::instance()->getTemplateJobContact($this->job);
        echo $template;

        return '<div class="job-page">' . ob_get_clean(). '</div>';
    }

    /**
     * Output specific Job
     *
     * @param Array $opt
     * @return string
     */
    public function outputFormFreeCandidate($opt)
    {
        global $wp_query;

        ob_start();

        $this->add_css('freeCandidateForm');
        $template = $this->freeCandidateForm();
        echo $template;

        return '<div class="free-candidate animated fadeIn">' . ob_get_clean(). '</div>';
    }
}

function NICOKAJOB()
{
    return NicokaJob::instance();
}

$GLOBALS['nicoka_job'] = NICOKAJOB();
?>