<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 *
 * @package Nicoka Job
 * @since 1.0.0
 */
class NicokaTemplate
{

    /**
     * The single instance of the class.
     *
     * @var self
     *
     */
    private static $_instance = null;

    /**
     *
     *
     * @staticw
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
        global $wp_version;
    }

    /**
     * Generate Job template
     *
     * @param object $job
     * @return void
     */
    public function getTemplateJob($job)
    {
        global $wp;

        $this->title__ = $job->label;
        ?>
        <div class="job-header animated fadeIn">
            <div class="ast-container">
                <div class="ast-row">
                    <div class="ast-col-lg-7 ast-col-md-7 ast-col-sm-12 ast-col-xs-12 animated fadeInLeft">
                        <h1><?php echo $job->label; ?></h1>
                    </div>
                </div>
                <div class="ast-row">
                    <div class="ast-col-lg-7 ast-col-md-7 ast-col-sm-12 ast-col-xs-12 animated fadeInLeft">
                        <p><?php echo nl2br($job->description); ?></p>
                    </div>
                    <div class="ast-col-lg-offset-1 ast-col-lg-4 ast-col-md-offset-1 ast-col-md-4 ast-col-sm-12 ast-col-xs-12 animated fadeInRight">
                        <div class="job-contract-type">
                            <i class="ico-fiche"></i> <?php echo $job->contract_type__formated; ?>
                        </div>
                        <div class="job-location">
                            <i class="ico-map"></i> <?php echo $job->city; ?>
                        </div>
                        <div class="job-reference">
                            Ref. <?php echo $job->reference; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="job-description animated fadeIn">
            <div class="ast-container">
                <div class="ast-row">
                    <div class="ast-col-lg-12 ast-col-md-12 ast-col-sm-12 ast-col-xs-12 animated fadeInUp">
                        <h2>Descriptif du poste</h2>
                        <p><?php echo nl2br($job->requirements); ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="job-profil animated fadeIn">
            <div class="ast-container">
                <div class="ast-row">
                    <div class="ast-col-lg-12 ast-col-md-12 ast-col-sm-12 ast-col-xs-12 animated fadeInUp">
                        <h2>Profil recherché</h2>
                        <p><?php echo nl2br($job->benefits); ?></p>
                        <div class="job-button-container">
                            <button class="button getform">Je postule</button>
                        </div>
                        <div class="sharethis-inline-share-buttons editorskit-no-desktop editorskit-no-tablet"></div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Generate Job Contact template
     *
     * @param object $job
     * @return void
     */
    public function getTemplateJobContact($job)
    {
        global $wp;

        $this->title__ = $job->label;
        $contact = $job->assign_to_objectid__formated;
            $contactImg = "https://www.omeva.fr/equipe/" . str_replace('é', 'e', str_replace(' ', '', strtolower($contact)));
        ?>
        <div class="job-contact animated fadeInUp">
            <div class="ast-container">
                <div class="ast-col-lg-12 ast-col-md-12 ast-col-sm-12 ast-col-xs-12">
                    <h2>Votre contact Omeva</h2>
                    <div class="wp-block-media-text is-stacked-on-mobile" style="grid-template-columns:30% auto">
                        <div class="wp-block-media-text__media">
                            <figure>
                                <?php if ($contact == 'Ingrid Pain') { ?>
                                    <img src="https://www.omeva.fr/ingrid-pain-offre"/>
                                <?php } else { ?>
                                    <img src="<?php echo $contactImg; ?>"/>
                                <?php } ?>

                            </figure>
                        </div>
                        <div class="wp-block-media-text__content">
                            <p>Je me tiens à votre disposition pour tout complément d'information concernant cette offre d'emploi à impact !</p>
                        </div>
                    </div>
                    <p class="name">
                        <?php if ($contact == 'Aurelie Jourdon') {
                            echo "Aurélie Jourdon";
                        } else {
                            echo nl2br($contact);
                        } ?>
                    </p>
                </div>
            </div>
        </div>

        <?php
    }

    /**
     * Generate HTML Filter Campus Selector
     *
     * @param [type] $job
     * @return void
     */
    private function getFormFilterCampus($filiere)
    {

        $campus = [];
        if (is_array($filiere)) {
            foreach ((array)$filiere as $job) {
                $campus[sanitize_title($job->location)] = ['label' => $job->location];
            }
        }
        ?>
        <div class="bloc-form">
            <div class="box-filtres-campus">
                <label>Filtrer par Campus</label>
                <select class="selectLieu">
                    <option value="Tous">Tous</option>
                    <?php foreach ((array)$campus as $keyCampus => $valCampus) { ?>
                        <option value="<?php echo $keyCampus; ?>"><?php echo $valCampus['label']; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <?php
    }

    /**
     * Generate HTML filter Jobs
     *
     * @param Array $filter
     * @return void
     */
    private function getFormFilterJobs($filter, $title, $id)
    {
        ?>
        <div>
            <label><?php echo $title ?></label>
            <select id="<?php echo $id ?>" class="select-filter">
                <option value="all">Tous</option>
                <?php foreach ((array)$filter as $key => $val) { ?>
                    <option value="<?php echo $key; ?>"><?php echo $val; ?></option>
                <?php } ?>
            </select>
        </div>
        <?php
    }

    /**
     * Transform Contract Type JSON object to Array with ID and label
     * ex : [{id : 1, label : 'CDI'}] is transform to [1 => ['label' => 'CDI']]
     * @param $contractTypes
     * @return array
     */
    private function getContractTypesArray($contractTypes)
    {
        $types = [];
        if ($contractTypes && is_array($contractTypes)) {
            foreach ((array)$contractTypes as $contractType) {
                $types[$contractType->id]['label'] = $contractType->label;
            }
        }

        return $types;
    }

    /**
     * Get HTML template job listing
     *
     * @param Array $jobs
     * @return void
     */
    public function getTemplateJobs($jobs, $types, $departments, $jobCategories)
    {
        ?>
        <form class="job_filters">
            <div class="bloc-form">
                <div class="box-filtres-campus">
                    <?php /*
                    if ($jobCategories && count($jobCategories) > 1) {
                        $this->getFormFilterJobs($jobCategories, "Secteur d'activité", "item-filter-category");
                    }
                    if ($departments && count($departments) > 1) {
                        $this->getFormFilterJobs($departments, "Ville", "item-filter-department");
                    }
                    if ($types && count($types) > 1) {
                        $this->getFormFilterJobs($types, "Type de contrat", "item-filter-type");
                    }
                    */
                    ?>
                </div>
            </div>
        </form>
        <div class="ast-container">
        <?php if (is_array($jobs) && count($jobs) > 0) {
        foreach ((array)$jobs as $job) {
            $url = "/" . OMEVA_PAGE_POST_URL . "/" . sanitize_title($job->label . "__" . $job->uid) . "/";
            ?>
            <div class="animated slideInUp job-item ast-col-lg-4 ast-col-md-4 ast-col-sm-12 ast-col-xs-12"
                 data-type="<?php echo $job->contract_type; ?>"
                 data-department="<?php echo sanitize_title($job->department); ?>"
                 data-category="<?php echo $job->categoryid; ?>">
                <div class="job-inner">
                    <div class="job-title">
                        <a href="<?php echo $url; ?>"><h3><?php echo $job->label; ?></h3></a>
                    </div>
                    <div class="job-contract-type">
                        <a href="<?php echo $url; ?>"><i
                                    class="ico-fiche"></i> <?php echo $job->contract_type__formated; ?> </a>
                    </div>
                    <div class="job-location">
                        <a href="<?php echo $url; ?>"><i class="ico-map"></i> <?php echo $job->city; ?> </a>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
        </div>
        <div>
            <p class="has-text-align-center animated has-text-color has-medium-font-size fadeInDown"
               style="color:#0c4f4d"><strong>Aucune offre ne correspond à votre profil ?</strong></p>
            <div class="wp-block-buttons is-content-justification-center animated omeva-buttons-container fadeInUp">
                <div class="wp-block-button omeva-police-first">
                    <a class="wp-block-button__link has-text-color has-background"
                       href="https://www.omeva.fr/candidature-spontanee/"
                       style="border-radius:25px;background-color:#0c4f4d;color:#f3efe2">Candidature spontanée</a>
                </div>
            </div>
        </div>
    <?php } else {
        ?>
        <p class="has-text-align-center animated has-text-color has-medium-font-size fadeInDown" style="color:#0c4f4d">
            <strong>Aucune offre disponible actuellement, n'hésitez pas à nous laisser votre candidature</strong></p>
        <div class="wp-block-buttons is-content-justification-center animated omeva-buttons-container fadeInUp">
            <div class="wp-block-button omeva-police-first">
                <a class="wp-block-button__link has-text-color has-background"
                   href="https://www.omeva.fr/candidature-spontanee/"
                   style="border-radius:25px;background-color:#0c4f4d;color:#f3efe2">Candidature spontanée</a>
            </div>
        </div>
        </div>
        <?php
    }
    }

    /**
     * Get HTML template job listing
     *
     * @param Array $jobs
     * @return void
     */
    public function getTemplateTeaserJobs($jobs)
    {
        if (is_array($jobs) && count($jobs) > 0) {
            ?>
            <div class="jobs-teaser">
            <h2 class="title animated slideInDown">Nos offres d'emploi à la une</h2>
            <div class="ast-container">
                <?php foreach ((array)$jobs as $job) {
                    $url = "/" . OMEVA_PAGE_POST_URL . "/" . sanitize_title($job->label . "__" . $job->uid) . "/";
                    ?>
                    <div class="animated slideInUp delay-200ms job-item ast-col-lg-4 ast-col-md-4 ast-col-sm-12 ast-col-xs-12">
                        <div class="job-inner">
                            <div class="job-title">
                                <a href="<?php echo $url; ?>"><h3><?php echo $job->label; ?></h3></a>
                            </div>
                            <div class="job-contract-type">
                                <a href="<?php echo $url; ?>"><i
                                            class="ico-fiche"></i> <?php echo $job->contract_type__formated; ?> </a>
                            </div>
                            <div class="job-location">
                                <a href="<?php echo $url; ?>"><i class="ico-map"></i> <?php echo $job->city; ?> </a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                echo '</div>';
                ?>
                <div class="cta"><a class="btn-underline" href="/candidats"><span>Voir toutes les offres</span><i
                                class="ico-arrow"></i></a></div>
            </div>
            <?php
        }
    }

    function set_title($titlePage)
    {
        return $titlePage;
    }
}

NicokaTemplate::instance();