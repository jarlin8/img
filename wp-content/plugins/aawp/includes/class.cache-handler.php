<?php
/**
 * Cache Handler
 *
 * @package     AAWP
 * @since       3.4.0
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'AAWP_Cache_Handler') ) {

    class AAWP_Cache_Handler {

        private $args;

        private $lists;
        private $products;

        private $max_list_renewals;
        private $max_product_renewals;
        private $max_product_rating_renewals;

        /**
         * Constructor
         */
        public function __construct() {

            $this->args = array(
                'orderby' => 'date_updated',
                'order' => 'ASC',
                'outdated' => true,
                'crawl_reviews' => false
            );

            $this->setup_max_renewals();
        }

        private function setup_max_renewals() {

            // Default values
            $product_renewals = 25; // Default = 100
            $list_renewals = 10; // Default = 50
            $product_rating_renewals = 10; // Default = 25

            // Database sizes
            $products_in_database = aawp_get_products_count();
            $lists_in_database = aawp_get_lists_count();

            //aawp_debug_display( $products_in_database, 'setup_max_renewals() >> $products_in_database' );
            //aawp_debug_display( $lists_in_database, 'setup_max_renewals() >> $lists_in_database' );

            // Maybe increase values
            //$products_in_database = 1500; // Debug only
            //$lists_in_database = 150; // Debug only

            if ( intval( $products_in_database ) > 1000 )
                $product_renewals = $product_renewals * 2;

            if ( intval( $lists_in_database ) > 100 )
                $list_renewals = $list_renewals * 1.5;

            // Set values for usage in class
            $this->max_product_renewals = $product_renewals;
            $this->max_list_renewals = $list_renewals;
            $this->max_product_rating_renewals = $product_rating_renewals;

            //$this->max_list_renewals = 1; // DEV only
            //$this->max_product_renewals = 2; // DEV only

            aawp_debug_display( $this->max_product_renewals, 'setup_max_renewals() >> $this->max_product_renewals' );
            aawp_debug_display( $this->max_list_renewals, 'setup_max_renewals() >> $this->max_list_renewals' );
            aawp_debug_display( $this->max_product_rating_renewals, 'setup_max_renewals() >> $this->max_product_rating_renewals' );
        }

        public function set_args( $args = array() ) {
            $this->args = wp_parse_args( $args, $this->args );
        }

        public function force_renewals() {
            $this->args['outdated'] = false;
        }

        public function crawl_reviews() {
            $this->args['crawl_reviews'] = true;
        }

        /**
         * Initiate renewals
         */
        public function renew() {

            /* TODO: Handle Smart Caching inside setups
            if ( ! $force )
                $smart_caching = aawp_smart_caching_activated();

            if ( $smart_caching )
                $default_args['renew_cache'] = true;
            */

            // Lists
            $this->setup_lists();
            //aawp_debug( $this->lists, '$this->lists' );

            // Products
            $this->setup_products();
            //aawp_debug( $this->products, '$this->products' );

            // Action!
            aawp_set_cache_last_update();

            /*
             * Prevent script timeout
             *
             * Source: http://infopotato.com/blog/index/php_timeout
             */
            $max_execution_time = ( ( sizeof( $this->products ) * 2 ) > 300 ) ? sizeof( $this->products ) * 2 : 300;
            $max_execution_time = apply_filters( 'aawp_update_cache_max_execution_time', $max_execution_time );
            ini_set('max_execution_time', $max_execution_time);

            $debug_start_time = microtime( true );

            try {

                if ( ! $this->args['outdated'] )

                // Update lists
                $this->renew_lists();

                // Update products
                $this->renew_products();

            } catch (Exception $e) {
                aawp_log( 'Product (Renew)', sprintf( '*** ERROR EXCEPTION: <code>%s</code> ***', $e->getMessage() ) );
            }

            $debug_execution_time = microtime(true) - $debug_start_time;
        }

        /**
         * Setup lists
         */
        private function setup_lists() {

            $default_list_args = array(
                'number' => $this->max_list_renewals
            );

            // Parse args
            $list_args = wp_parse_args( $this->args, $default_list_args );

            aawp_debug_display( $list_args, 'setup_lists >> $list_args' );

            $lists = aawp_get_lists( $list_args );

            $this->lists = ( is_array( $lists ) && sizeof( $lists ) > 0 ) ? $lists : array();

            // Less lists than maximum? Add more products instead
            $lists_treshold = $this->max_list_renewals - sizeof( $this->lists );

            aawp_debug_display( $lists_treshold, '$lists_treshold' );
            aawp_debug_display( $this->max_product_renewals, '$this->max_product_renewals BEFORE $lists_treshold' );

            if ( $lists_treshold )
                $this->max_product_renewals = $this->max_product_renewals + ( $lists_treshold * 5 );

            aawp_debug_display( $this->max_product_renewals, '$this->max_product_renewals AFTER $lists_treshold' );
        }

        /**
         * Setup products
         */
        private function setup_products() {

            $default_product_args = array(
                'number' => $this->max_product_renewals,
                'status' => 'active'
            );

            // Parse args
            $product_args = wp_parse_args( $this->args, $default_product_args );

            aawp_debug_display( $product_args, 'setup_products >> $product_args' );

            $products = aawp_get_products( $product_args );

            $this->products = ( is_array( $products ) && sizeof( $products ) > 0 ) ? $products : array();

            aawp_debug_display( sizeof( $this->products ), 'setup_products: $this->products' );
        }

        /**
         * Renew lists
         */
        private function renew_lists() {

            if ( ! is_array( $this->lists ) || sizeof( $this->lists ) == 0 )
                return;

            aawp_debug_display( sizeof( $this->lists ), 'lists BEFORE update' );

            $i = 0;

            foreach ( $this->lists as $list_data ) {

                $renewed = aawp_renew_list( $list_data );

                if ( $renewed )
                    $i++;
            }
        }

        /**
         * Renew products
         */
        private function renew_products() {

            if ( ! is_array( $this->products ) || sizeof( $this->products ) == 0 )
                return;

            aawp_debug_display( sizeof( $this->products ), 'products BEFORE update' );

            $renew_products_args = array(
                'crawl_reviews' => $this->args['crawl_reviews']
            );

            $renewed = aawp_renew_products( $this->products, $renew_products_args );

            //echo 'Products renewed: ' . $renewed . '<br>';

            if ( $renewed > 0 ) {
                aawp_log( 'Product (Renew)', sprintf( wp_kses( _n( '<code>%d</code> cached product renewed via API.', '<code>%d</code> cached products renewed via API.', absint( $renewed ), 'aawp' ), [ 'code' => [] ] ), absint( $renewed ) ) );
            }
        }

        /**
         * Initiate renew product ratings
         */
        public function renew_ratings() {

            /* TODO: Handle Smart Caching inside setups
            if ( ! $force )
                $smart_caching = aawp_smart_caching_activated();

            if ( $smart_caching )
                $default_args['renew_cache'] = true;
            */

            // First: Search for products without ratings
            $product_args = array(
                'orderby' => 'reviews_updated',
                'order' => 'ASC',
                'number' => ( $this->max_product_rating_renewals / 2 ),
                'status' => 'active',
                'reviews_outdated' => true,
                'has_reviews' => false
            );

            $products = aawp_get_products( $product_args );

            //aawp_debug_display( $products, 'FIRST: $products' );
            aawp_debug_display( 'FIRST: $products >> ' . sizeof( $products ) );

            /*
            foreach ( $products as $product_id ) {
                echo $product_id . ' >> ';
                var_dump(aawp_get_product_reviews( $product_id ) );
                echo '<br>';
            }
            */

            // Second: Fill-up with products holding ratings
            if ( empty( $products ) || ( is_array( $products ) && sizeof( $products ) < $this->max_product_rating_renewals ) ) {

                if ( empty( $products ) )
                    $products = array();

                $fillup_product_args = array(
                    'orderby' => 'reviews_updated',
                    'order' => 'ASC',
                    'number' => $this->max_product_rating_renewals - sizeof( $products ),
                    'status' => 'active',
                    'reviews_outdated' => true,
                    'has_reviews' => true
                );

                $fillup_products = aawp_get_products( $fillup_product_args );

                //aawp_debug_display( $fillup_products, 'SECOND: $fillup_products' );
                aawp_debug_display( 'SECOND: $fillup_products >> ' . sizeof( $fillup_products ) );

                if ( is_array( $fillup_products ) ) {
                    $products = array_merge( $products, $fillup_products );
                }
            }

            $this->products = ( is_array( $products ) && sizeof( $products ) > 0 ) ? $products : array();

            //aawp_debug_display( $this->products, 'renew_ratings: $this->products' );
            aawp_debug_display( 'renew_ratings: $this->products >> ' . sizeof( $this->products ) );

            /*
             * Prevent script timeout
             *
             * Source: http://infopotato.com/blog/index/php_timeout
             */
            $max_execution_time = ( ( sizeof( $this->products ) * 2 ) > 300 ) ? sizeof( $this->products ) * 2 : 300;
            $max_execution_time = apply_filters( 'aawp_update_rating_cache_max_execution_time', $max_execution_time );
            ini_set('max_execution_time', $max_execution_time);

            $debug_start_time = microtime( true );

            try {

                if ( ! $this->args['outdated'] )

                // Update products
                $this->renew_product_ratings();

            } catch (Exception $e) {
                aawp_log( 'Product (Rating)', sprintf( '*** ERROR EXCEPTION: <code>%s</code> ***', $e->getMessage() ) );
            }

            $debug_execution_time = microtime(true) - $debug_start_time;
        }

        /**
         * Renew ratings
         */
        private function renew_product_ratings() {

            if ( ! is_array( $this->products ) || sizeof( $this->products ) == 0 )
                return;

            $renewed = aawp_renew_product_reviews( $this->products );
        }
    }
}
