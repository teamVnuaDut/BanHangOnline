<?php
/**
 * AWS plugin hooks for specific languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AWS_Langs' ) ) :

    /**
     * Class for main plugin functions
     */
    class AWS_Langs {

        private $lang = '';

        private $data = array();

        /**
         * @var AWS_Langs The single instance of the class
         */
        protected static $_instance = null;

        /**
         * Main AWS_Langs Instance
         *
         * Ensures only one instance of AWS_Langs is loaded or can be loaded.
         *
         * @static
         * @return AWS_Langs - Main instance
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Constructor
         */
        public function __construct() {

            $reindex_version = AWS()->option_vars->get_reindex_version();

            // On search start
            add_filter( 'aws_search_current_lang', array( $this, 'aws_search_current_lang' ) );

            // On index start
            add_action( 'aws_index_before_scrapping', array( $this, 'aws_index_before_scrapping' ), 10, 4 );

            // Set current lang code for scrapping functions
            add_filter( 'aws_current_scrapping_lang', array( $this, 'aws_current_scrapping_lang' ), 1 );

            // Plural form of words for diff languages
            if ( ( $reindex_version && version_compare( $reindex_version, '3.16', '>=' ) ) ||
                ( is_ajax() && isset( $_REQUEST['action'] ) && $_REQUEST['action'] === 'aws-reindex' ) )
            {
                add_filter( 'aws_plurals_singular_rules', array( $this, 'aws_plurals_singular_rules' ), 10, 2 );
                add_filter( 'aws_diacritic_chars', array( $this, 'aws_diacritic_chars' ), 1  );
            }

        }

        /*
         * Get lang during search
         */
        public function aws_search_current_lang( $lang ) {

            if ( $lang ) {
                $this->lang = $lang;
            } else {
                $this->lang = get_locale();
            }

            return $lang;

        }

        /*
         * Get lang during index
         */
        public function aws_index_before_scrapping( $product, $id, $lang, $options ) {

            if ( $lang ) {
                $this->lang = $lang;
            } else {
                $this->lang = get_locale();
            }

        }

        /*
         * Set current lang
         */
        public function aws_current_scrapping_lang( $lang ) {

            if ( $this->lang ) {
                $lang = strtolower( substr( $this->lang, 0, 2 ) );
            }

            return $lang;

        }

        /*
         * Switch singular form for diff languages
         */
        public function aws_plurals_singular_rules( $rules, $lang ) {

            $lang = strtolower( substr( $lang, 0, 2 ) );

            $plurals_rules = $this->plulars_rules();

            if ( $plurals_rules && isset( $plurals_rules[$lang] ) ) {
                $rules = $plurals_rules[$lang];
            }

            return $rules;

        }

        /*
         * Replace special langs characters
         */
        public function aws_diacritic_chars( $chars ) {

            $lang = strtolower( substr( $this->lang, 0, 2 ) );

            if ( $lang && $lang === 'el' ) {
                $greek_to_lation = $this->greek_to_latin_chars();
                $chars = array_merge( $chars, $greek_to_lation );
            }

            return $chars;

        }

        /*
         * Plurals rules for different languages
         */
        private function plulars_rules() {

            $plurals = array();

            $plurals['it'] = array(
                '/uomini$/i' => 'uomo',
                '/dei$/i' => 'dio',
                '/abili$/i' => 'abile',
                '/che$/i' => 'ca',
                '/ce$/i' => 'cia',
                '/ghe/i' => 'ga',
                '/chi$/i' => 'co',
                '/ghi$/i' => 'go',
                '/ci$/i' => 'ca',
                '/gi$/i' => 'ga',
                '/ni$/i' => 'ne',
                '/ie$/i' => 'io',
                '/ii$/i' => 'io',
                '/i$/i' => 'o',
                '/e$/i' => 'a',
            );

            $plurals['de'] = array(
                '/(en)$/i' => '',
                '/(er)$/i' => '',
                '/(e)$/i' => '',
                '/(n)$/i' => '',
                '/(s)$/i' => '',
            );

            $plurals['es'] = array(
                '/ces$/i' => 'z',
                '/es$/i' => '',
                '/s$/i' => '',
            );

            $plurals['pt'] = array(
                '/(ões)$/i' => 'ão',
                '/(ais)$/i' => 'al',
                '/(éis)$/i' => 'el',
                '/(óis)$/i' => 'ol',
                '/(ns)$/i'  => 'm',
                '/(es)$/i'  => 'e',
                '/(is)$/i'  => 'is',
                '/(s)$/i'   => '',
            );

            $plurals['fr'] = array(
                '/(aux)$/i' => 'al',
                '/(eaux)$/i' => 'eau',
                '/(eaux)$/i' => 'eau',
                '/(us)$/i' => 'us',
                '/(s)$/i' => '',
                '/(x)$/i' => '',
            );

            $plurals['ru'] = array(
                '/(ы)$/iu' => '',
            );

            return $plurals;

        }

        /*
         * Greek to lation chars array
         */
        private function greek_to_latin_chars() {

            $greek_to_latin_chars = array(
                'ευα' => 'eva',
                'ευη' => 'evi',
                'ευε' => 'eve',
                'ευκ' => 'efk',
                'ευμ' => 'evm',
                'χτ'  => 'kt',
                'ντ'  => 'nt',
                'ευ'  => 'ef',
                'ά'   => 'a',
                'ώ'   => 'o',
                'έ'   => 'e',
                'ό'   => 'o',
                'ί'   => 'i',
                'ή'   => 'i',
                'ϊ'   => 'i',
                'ϋ'   => 'i',
                'ύ'   => 'i',
                'λλ'  => 'l',
                'σσ'  => 's',
                'τζ'  => 'g',
                'γγ'  => 'g',
                'γκ'  => 'g',
                'κκ'  => 'k',
                'μμ'  => 'm',
                'νν'  => 'n',
                'φφ'  => 'f',
                'ρρ'  => 'r',
                'ττ'  => 't',
                'αι'  => 'e',
                'οι'  => 'i',
                'ει'  => 'i',
                'υι'  => 'i',
                'ου'  => 'ou',
                'σ'   => 's',
                'τ'   => 't',
                'υ'   => 'i',
                'φ'   => 'f',
                'χ'   => 'x',
                'ψ'   => 'ps',
                'ω'   => 'o',
                'α'   => 'a',
                'β'   => 'v',
                'γ'   => 'g',
                'δ'   => 'd',
                'ε'   => 'e',
                'ζ'   => 'z',
                'η'   => 'i',
                'θ'   => '',
                'ι'   => 'i',
                'κ'   => 'k',
                'λ'   => 'l',
                'μ'   => 'm',
                'ν'   => 'n',
                'ξ'   => 'ks',
                'ο'   => 'o',
                'ρ'   => 'r',
                'π'   => 'p',
            );

            return $greek_to_latin_chars;

        }

    }

endif;