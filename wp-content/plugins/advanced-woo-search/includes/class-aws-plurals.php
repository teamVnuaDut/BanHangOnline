<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'AWS_Plurals' ) ) :

    /**
     * Class for plurals words forms
     */
    class AWS_Plurals {

        /**
         * Singular rules
         *
         * @var array
         */
        protected static $_singular = array(
            '/(s)tatuses$/i' => '\1\2tatus',
            '/^(.*)(menu)s$/i' => '\1\2',
            '/(quiz)zes$/i' => '\\1',
            '/(matr)ices$/i' => '\1ix',
            '/(vert|ind)ices$/i' => '\1ex',
            '/^(ox)en/i' => '\1',
            '/(alias)(es)*$/i' => '\1',
            '/(alumn|bacill|cact|foc|fung|nucle|radi|stimul|syllab|termin|viri?)i$/i' => '\1us',
            '/([ftw]ax)es/i' => '\1',
            '/(cris|ax|test)es$/i' => '\1is',
            '/(shoe)s$/i' => '\1',
            '/(o)es$/i' => '\1',
            '/ouses$/' => 'ouse',
            '/([^a])uses$/' => '\1us',
            '/([m|l])ice$/i' => '\1ouse',
            '/(x|ch|ss|sh)es$/i' => '\1',
            '/(m)ovies$/i' => '\1\2ovie',
            '/(s)eries$/i' => '\1\2eries',
            '/([^aeiouy]|qu)ies$/i' => '\1y',
            '/(tive)s$/i' => '\1',
            '/(hive)s$/i' => '\1',
            '/(drive)s$/i' => '\1',
            '/(^analy)ses$/i' => '\1sis',
            '/(analy|diagno|^ba|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/i' => '\1\2sis',
            '/([ti])a$/i' => '\1um',
            '/(p)eople$/i' => '\1\2erson',
            '/(m)en$/i' => '\1an',
            '/(c)hildren$/i' => '\1\2hild',
            '/(n)ews$/i' => '\1\2ews',
            '/eaus$/' => 'eau',
            '/^(.*us)$/' => '\\1',
            '/s$/i' => ''
        );

        /**
         * Irregular rules
         *
         * @var array
         */
        protected static $_irregular = array(
            'atlases' => 'atlas',
            'beefs' => 'beef',
            'briefs' => 'brief',
            'brothers' => 'brother',
            'cafes' => 'cafe',
            'children' => 'child',
            'cookies' => 'cookie',
            'corpuses' => 'corpus',
            'cows' => 'cow',
            'criteria' => 'criterion',
            'ganglions' => 'ganglion',
            'genies' => 'genie',
            'genera' => 'genus',
            'graffiti' => 'graffito',
            'hoofs' => 'hoof',
            'loaves' => 'loaf',
            'men' => 'man',
            'monies' => 'money',
            'mongooses' => 'mongoose',
            'moves' => 'move',
            'mythoi' => 'mythos',
            'niches' => 'niche',
            'numina' => 'numen',
            'occiputs' => 'occiput',
            'octopuses' => 'octopus',
            'opuses' => 'opus',
            'oxen' => 'ox',
            'penises' => 'penis',
            'sexes' => 'sex',
            'soliloquies' => 'soliloquy',
            'testes' => 'testis',
            'trilbys' => 'trilby',
            'turfs' => 'turf',
            'potatoes' => 'potato',
            'heroes' => 'hero',
            'teeth' => 'tooth',
            'geese' => 'goose',
            'feet' => 'foot',
            'foes' => 'foe',
            'sieves' => 'sieve',
            'caches' => 'cache',
        );

        /**
         * Words that should not be inflected
         *
         * @var array
         */
        protected static $_uninflected = array(
            '.*[nrlm]ese', '.*data', '.*deer', '.*fish', '.*measles', '.*ois',
            '.*pox', '.*sheep', 'feedback', 'stadia', '.*?media',
            'chassis', 'clippers', 'debris', 'diabetes', 'equipment', 'gallows',
            'graffiti', 'headquarters', 'information', 'innings', 'news', 'nexus',
            'pokemon', 'proceedings', 'research', 'sea[- ]bass', 'series', 'species', 'weather'
        );

        /**
         * Method cache array.
         *
         * @var array
         */
        protected static $_cache = array();

        /**
         * Cache inflected values, and return if already available
         *
         * @param string $type Inflection type
         * @param string $key Original value
         * @param string|bool $value Inflected value
         * @return string|false Inflected value on cache hit or false on cache miss.
         */
        protected static function _cache($type, $key, $value = false)
        {
            $key = '_' . $key;
            $type = '_' . $type;
            if ($value !== false) {
                static::$_cache[$type][$key] = $value;
                return $value;
            }
            if (!isset(static::$_cache[$type][$key])) {
                return false;
            }
            return static::$_cache[$type][$key];
        }

        /**
         * Return $word in singular form.
         *
         * @param string $word Word in plural
         * @return string Word in singular
         */
        public static function singularize( $word, $lang = 'en' ) {

            if ( isset( static::$_cache['singularize'][$lang][$word] ) ) {
                return static::$_cache['singularize'][$lang][$word];
            }

            if ( isset( static::$_irregular[$word] ) ) {
                static::$_cache['singularize'][$lang][$word] = static::$_irregular[$word];
                return static::$_cache['singularize'][$lang][$word];
            }

            if ( !isset( static::$_cache['uninflected'] ) ) {
                static::$_cache['uninflected'] = '(?:' . implode('|', static::$_uninflected) . ')';
            }

            if ( !isset( static::$_cache['singularize_rules'][$lang] ) ) {
                static::$_cache['singularize_rules'][$lang] = apply_filters( 'aws_plurals_singular_rules', static::$_singular, $lang );
            }

            if ( preg_match( '/^(' . static::$_cache['uninflected'] . ')$/i', $word, $regs ) ) {
                static::$_cache['singularize'][$lang][$word] = $word;
                return $word;
            }

            foreach ( static::$_cache['singularize_rules'][$lang] as $rule => $replacement ) {
                if ( preg_match($rule, $word ) ) {
                    static::$_cache['singularize'][$lang][$word] = preg_replace( $rule, $replacement, $word );
                    return static::$_cache['singularize'][$lang][$word];
                }
            }

            static::$_cache['singularize'][$lang][$word] = $word;

            return $word;

        }

    }

endif;
