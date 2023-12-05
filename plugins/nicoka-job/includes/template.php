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
                        <h1 class="title-job-detail"><?php echo $job->label; ?></h1>
                    </div>
                </div>
                <div class="ast-row">
                    <div class="ast-col-lg-7 ast-col-md-7 ast-col-sm-12 ast-col-xs-12 animated fadeInLeft">
                        <p class="text-job-intro">
                        Omeva est un cabinet de recrutement engagé, dédié aux métiers de la transition sociale, sociétale et environnementale.<br><br>
                        Notre mission est de vous mettre en relation avec des organisations soucieuses de leurs impacts, selon vos compétences et vos valeurs, afin d'œuvrer ensemble pour un futur souhaitable.<br><br>
                        </p>
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
                        <p><?php echo nl2br($job->description); ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="job-profil animated fadeIn">
            <div class="ast-container">
                <div class="ast-row">
                    <div class="ast-col-lg-12 ast-col-md-12 ast-col-sm-12 ast-col-xs-12 animated fadeInUp">
                        <h2>Profil recherché</h2>
                        <p><?php echo nl2br($job->requirements); ?></p>
                        <div class="job-button-container">
                            <button class="button getform">Je postule</button>
                        </div>
                        <div class="share-buttons-job">
                            <div class="share-button linkedin-share-button st-custom-button" data-network="linkedin">
                                <i class="fa-brands fa-linkedin-in"></i>
                            </div> 
                            <div class="share-button email-share-button st-custom-button" data-network="email" data-email-subject="Offre d'emploi à impact">
                                <i class="fa-regular fa-envelope"></i>
                            </div> 
                            <div class="share-button facebook-share-button st-custom-button" data-network="facebook">
                                <i class="fa-brands fa-facebook-f"></i>
                            </div> 
                        </div>  
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Generate Job template
     *
     * @param object $job
     * @return void
     */
        public function getTemplateUnavailableJob($job)
    {
        global $wp;

        $this->title__ = $job->label;
        ?>
        <div class="job-header animated fadeIn">
            <div class="ast-container">
                <div class="ast-row">
                    <div class="ast-col-lg-7 ast-col-md-7 ast-col-sm-12 ast-col-xs-12 animated fadeInLeft">
                        <h1 class="title-job-detail"><?php echo $job->label; ?></h1>
                    </div>
                </div>
                <div class="ast-row">
                    <div class="ast-col-sm-12 ast-col-xs-12 animated fadeInLeft">
                        <p class="text-job-intro">Malheureusement cette offre a été pourvue, n’hésitez pas à consulter nos autres offres d’emploi.</p>
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
                                <img src="<?php echo $contactImg; ?>"/>
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
    private function getFormFilterJobsNew($filter, $title, $id)
    {
        ?>
        <script type="text/javascript">
            var categories = <?php echo json_encode($filter); ?>;
        </script>

        <div class="filter_form_offer_inline">
            <!-- bloc cities from JSON file -->
            <div class="dropdown-city-container" id="container-city" title="Séléctionnez une ou plusieurs catégories">
                <span class="dropdown-element select-city" id="select-city">
                </span>
            </div>
            <div>
                <input type="button" id="btn-filter-reset" value="Réinitialiser">
                <input type="button" id="btn-filter-offer" value="Rechercher">
            </div>
            
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
    public function getTemplateJobsNew($jobs, $jobCategories)
    {
        ?>
        <form class="job_filters">
            <div class="bloc-form">
                <div class="box-filtres-campus">
                    <?php 
                    if ($jobCategories && count($jobCategories) > 1) {
                        $this->getFormFilterJobsNew($jobCategories, "Secteur d'activité", "item-filter-category");
                    }
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
                        <h3><?php echo $job->label; ?></h3>
                    </div>
                    <div class="job-contract-type">
                        <i class="ico-fiche"></i> <?php echo $job->contract_type__formated; ?>
                    </div>
                    <div class="job-location">
                        <a href="<?php echo $url; ?>"><i class="ico-map"></i> <?php echo $job->city; ?></a>
                    </div>
                    <a class="job-block-clickable" href="<?php echo $url; ?>"></a>
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
     * Get HTML template job teaser for home
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
                                <h3><?php echo $job->label; ?></h3>
                            </div>
                            <div class="job-contract-type">
                                <i class="ico-fiche"></i> <?php echo $job->contract_type__formated; ?>
                            </div>
                            <div class="job-location">
                                <a href="<?php echo $url; ?>"><i class="ico-map"></i> <?php echo $job->city; ?></a>
                            </div>
                            <a class="job-block-clickable" href="<?php echo $url; ?>"></a>
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

    /**
     * Get HTML template jobs teaser for unavailable job
     *
     * @param Array $jobs
     * @return void
     */
    public function getTemplateTeaserJobsByCategory($jobs)
    {
        if (is_array($jobs) && count($jobs) > 0) {
        ?>
            <div class="jobs-teaser">
                <h2 class="title animated slideInDown">Découvrez nos offres similaires</h2>
                <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    <?php foreach ((array)$jobs as $job) {
                        $url = "/" . OMEVA_PAGE_POST_URL . "/" . sanitize_title($job->label . "__" . $job->uid) . "/";
                        ?>
                        <div class="animated slideInUp delay-200ms job-item swiper-slide">
                            <div class="job-inner">
                                <div class="job-title">
                                    <h3><?php echo $job->label; ?></h3>
                                </div>
                                <div class="job-contract-type">
                                    <i class="ico-fiche"></i> <?php echo $job->contract_type__formated; ?>
                                </div>
                                <div class="job-location">
                                    <a href="<?php echo $url; ?>"><i class="ico-map"></i> <?php echo $job->city; ?></a>
                                </div>
                                <a class="job-block-clickable" href="<?php echo $url; ?>"></a>
                            </div>
                        </div>
                    <?php } ?>
                   </div>
                <div class="swiper-pagination"></div>
            </div>
            <div class="swiper-button-prev-unique">
                <svg
                class="swiper-button-prev-svg"
                viewBox="137.718 -1.001 366.563 644">
                <path
                    d="M428.36 12.5c16.67-16.67 43.76-16.67 60.42 0 16.67 16.67 16.67 43.76 0 60.42L241.7 320c148.25 148.24 230.61 230.6 247.08 247.08 16.67 16.66 16.67 43.75 0 60.42-16.67 16.66-43.76 16.67-60.42 0-27.72-27.71-249.45-249.37-277.16-277.08a42.308 42.308 0 0 1-12.48-30.34c0-11.1 4.1-22.05 12.48-30.42C206.63 234.23 400.64 40.21 428.36 12.5z"></path>
                </svg>
            </div>
            <div class="swiper-button-next-unique">
                <svg class="swiper-button-next-svg" viewBox="0 0 238.003 238.003">
                <path
                    d="M181.776 107.719L78.705 4.648c-6.198-6.198-16.273-6.198-22.47 0s-6.198 16.273 0 22.47l91.883 91.883-91.883 91.883c-6.198 6.198-6.198 16.273 0 22.47s16.273 6.198 22.47 0l103.071-103.039a15.741 15.741 0 0 0 4.64-11.283c0-4.13-1.526-8.199-4.64-11.313z"
                ></path>
                </svg>
            </div>

            <!-- Swiper JS -->
            <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

            <!-- Initialize Swiper -->
            <script>
                var swiper = new Swiper(".mySwiper", {
                loop: true,
                slidesPerView: 1,
                spaceBetween: 30,
                navigation: {
                    nextEl: '.swiper-button-next-unique',
                    prevEl: '.swiper-button-prev-unique',
                },
                breakpoints: {
                    // when window width is >= 500px
                    500: {
                    slidesPerView: 2,
                    spaceBetween: 20
                    },
                    // when window width is >= 700px
                    700: {
                    slidesPerView: 3,
                    spaceBetween: 30
                    }
                }
                });
            </script>
                <?php
                }
        }

    /**
     * Get HTML template Omeva job teaser
     *
     * @param Array $jobs
     * @return void
     */
    public function getTemplateTeaserJobsOmeva($jobs)
    {
        if (is_array($jobs) && count($jobs) > 0) {
            ?>
        <div class="jobs-teaser">
            <h2 class="title animated slideInDown">Rejoignez notre équipe</h2>
            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    <?php foreach ((array)$jobs as $job) {
                        $url = "/" . OMEVA_PAGE_POST_URL . "/" . sanitize_title($job->label . "__" . $job->uid) . "/";
                        ?>
                        <div class="animated slideInUp delay-200ms job-item swiper-slide">
                            <div class="job-inner">
                                <div class="job-title">
                                    <h3><?php echo $job->label; ?></h3>
                                </div>
                                <div class="job-contract-type">
                                    <i class="ico-fiche"></i> <?php echo $job->contract_type__formated; ?>
                                </div>
                                <div class="job-location">
                                    <a href="<?php echo $url; ?>"><i class="ico-map"></i> <?php echo $job->city; ?></a>
                                </div>
                                <a class="job-block-clickable" href="<?php echo $url; ?>"></a>
                            </div>
                        </div>
                    <?php } ?>
                   </div>
                <div class="swiper-pagination"></div>
            </div>
            <div class="swiper-button-prev-unique">
                <svg
                class="swiper-button-prev-svg"
                viewBox="137.718 -1.001 366.563 644">
                <path
                    d="M428.36 12.5c16.67-16.67 43.76-16.67 60.42 0 16.67 16.67 16.67 43.76 0 60.42L241.7 320c148.25 148.24 230.61 230.6 247.08 247.08 16.67 16.66 16.67 43.75 0 60.42-16.67 16.66-43.76 16.67-60.42 0-27.72-27.71-249.45-249.37-277.16-277.08a42.308 42.308 0 0 1-12.48-30.34c0-11.1 4.1-22.05 12.48-30.42C206.63 234.23 400.64 40.21 428.36 12.5z"></path>
                </svg>
            </div>
            <div class="swiper-button-next-unique">
                <svg class="swiper-button-next-svg" viewBox="0 0 238.003 238.003">
                <path
                    d="M181.776 107.719L78.705 4.648c-6.198-6.198-16.273-6.198-22.47 0s-6.198 16.273 0 22.47l91.883 91.883-91.883 91.883c-6.198 6.198-6.198 16.273 0 22.47s16.273 6.198 22.47 0l103.071-103.039a15.741 15.741 0 0 0 4.64-11.283c0-4.13-1.526-8.199-4.64-11.313z"
                ></path>
                </svg>
            </div>

            <!-- Swiper JS -->
            <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

            <!-- Initialize Swiper -->
            <script>
                var swiper = new Swiper(".mySwiper", {
                loop: true,
                slidesPerView: 1,
                spaceBetween: 30,
                navigation: {
                    nextEl: '.swiper-button-next-unique',
                    prevEl: '.swiper-button-prev-unique',
                },
                breakpoints: {
                    // when window width is >= 500px
                    500: {
                    slidesPerView: 2,
                    spaceBetween: 20
                    },
                    // when window width is >= 700px
                    700: {
                    slidesPerView: 3,
                    spaceBetween: 30
                    }
                }
                });
            </script>
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