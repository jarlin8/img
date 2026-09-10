<?php

/**
 * A holder for utility methods that are useful to multiple classes.
 * Not intended as a catch-all for any method that doesn't seem to have a place to live
 */
class Wpil_Toolbox
{
    private static $encryption_possible = null;




    /**
     * Check if OpenSSL is available and encryption is not disabled with filter.
     *
     * @return bool Whether encryption is possible or not.
     */
    public static function is_available(){
        static $encryption_possible;
        if(null === $encryption_possible){
            $encryption_possible = extension_loaded('openssl');
        }

        return (bool) $encryption_possible;
    }

    /**
     * Get encryption key.
     *
     * @return string Key.
     */
    public static function get_key(){
        if(defined('LOGGED_IN_KEY') && '' !== LOGGED_IN_KEY){
            return LOGGED_IN_KEY;
        }

        return '';
    }

    /**
     * Get salt.
     *
     * @return string Salt.
     */
    public static function get_salt(){
        if(defined('LOGGED_IN_SALT') && '' !== LOGGED_IN_SALT){
            return LOGGED_IN_SALT;
        }

        return '';
    }

    /**
     * Encrypt data.
     * 
     * @param  mixed $value Original string.
     * @return string       Encrypted string.
     */
    public static function encrypt($value){
        if(!self::is_available()){
            return $value;
        }

        $method  = 'aes-256-ctr';
        $ciphers = openssl_get_cipher_methods();
        if(!in_array($method, $ciphers, true)){
            $method = $ciphers[0];
        }

        $ivlen = openssl_cipher_iv_length($method);
        $iv    = openssl_random_pseudo_bytes($ivlen);

        $raw_value = openssl_encrypt($value . self::get_salt(), $method, self::get_key(), 0, $iv);
        if(!$raw_value){
            return $value;
        }

        return base64_encode($iv . $raw_value);
    }

    /**
     * Decrypt string.
     *
     * @param  string $raw_value Encrypted string.
     * @return string            Decrypted string.
     */
    public static function decrypt($raw_value){
        if(!self::is_available()){
            return $raw_value;
        }

        $method  = 'aes-256-ctr';
        $ciphers = openssl_get_cipher_methods();
        if(!in_array($method, $ciphers, true)){
            $method = $ciphers[0];
        }

        $raw_value = base64_decode($raw_value, true);

        $ivlen = openssl_cipher_iv_length($method);
        $iv    = substr($raw_value, 0, $ivlen);

        $raw_value = substr($raw_value, $ivlen);

        if(!$raw_value || strlen($iv) !== $ivlen){
            return $raw_value;
        }

        $salt = self::get_salt();

        $value = openssl_decrypt($raw_value, $method, self::get_key(), 0, $iv);
        if(!$value || substr($value, - strlen($salt)) !== $salt){
            return $raw_value;
        }

        return substr($value, 0, - strlen($salt));
    }

    /**
     * Recursively encrypt array of strings.
     *
     * @param  mixed $data Original strings.
     * @return string       Encrypted strings.
     */
    public static function deep_encrypt($data){
        if(is_array($data)){
            $encrypted = [];
            foreach($data as $key => $value){
                $encrypted[self::encrypt($key)] = self::deep_encrypt($value);
            }

            return $encrypted;
        }

        return self::encrypt($data);
    }

    /**
     * Recursively decrypt array of strings.
     *
     * @param  string $data Encrypted strings.
     * @return string       Decrypted strings.
     */
    public static function deep_decrypt($data){
        if(is_array($data)){
            $decrypted = [];
            foreach($data as $key => $value){
                $decrypted[self::decrypt($key)] = self::deep_decrypt($value);
            }

            return $decrypted;
        }

        return self::decrypt($data);
    }


    /**
     * Escapes strings for "LIKE" queries
     **/
    public static function esc_like($string = ''){
        global $wpdb;
        return '%' . $wpdb->esc_like($string) . '%';
    }

    /**
     * Gets if custom rules have been added to the .htaccess file
     **/
    public static function is_using_custom_htaccess(){
        // Check if a .htaccess file exists.
		if(defined('ABSPATH') && is_file(ABSPATH . '.htaccess')){
			// If the file exists, grab the content of it.
			$htaccess_content = file_get_contents(ABSPATH . '.htaccess');

			// Filter away the core WordPress rules.
			$filtered_htaccess_content = trim(preg_replace('/\# BEGIN WordPress[\s\S]+?# END WordPress/si', '', $htaccess_content));

            // return if there's anything still in the file
            return !empty($filtered_htaccess_content);
		}

        return false;
    }

    /**
     * Gets the current action hook priority that is being executed.
     * 
     * @return int|bool Returns the priority of the currently executed hook if possible, and false if it is not.
     **/
    public static function get_current_action_priority(){
        global $wp_filter;

        $filter_name = current_filter();
        if(isset($wp_filter[$filter_name])){
            $filter_instance = $wp_filter[$filter_name];
            if(method_exists($filter_instance, 'current_priority')){
                return $filter_instance->current_priority();
            }
        }

        return false;
    }

    /**
     * Attempts to clear the CDN cache for a specific post
     **/
    public static function attempt_cdn_clearing($post_id, $type){
        // exit if we're not supposed to be clearing the cache
        if(empty($post_id) || !Wpil_Settings::clear_cdn()){
            return;
        }

        // if WP Rocket is available
        if(function_exists('rocket_clean_post') && $type === 'post'){
            // try using it to clear the cache
            rocket_clean_post($post_id);
        }elseif(function_exists('rocket_clean_term') && $type === 'term'){
            $term = get_term($post_id);
            if(!empty($term) && !is_a($term, 'WP_Error')){
                rocket_clean_post($post_id, $term->taxonomy);
            }
        }else{
            self::clear_varnish_cache($post_id, $type);
        }
    }


    /**
     * Makes a call to attempt to clear the Varnish cache for a specific post
     **/
    public static function clear_varnish_cache($post_id, $type = 'post'){
        // create our post object
        $post = new Wpil_Model_Post($post_id, $type);

        // try getting it's view link        
        $view_link = $post->getViewLink();

        // if that didn't work
        if(empty($view_link)){
            // exit
            return;
        }

		$url_parts = wp_parse_url($view_link);

        if(!isset($url_parts['host']) || empty($url_parts['host'])){
            return;
        }

        // obtain the information that we'll need to make the ping
        $protocol = ((isset($url_parts['scheme'])) ? $url_parts['scheme']: (is_ssl() ? 'https': 'http')) . '://';
        $host = $url_parts['host']; // todo consider pulling the site host if this misses.
        $path = (isset($url_parts['path'])) ? $url_parts['path'] : '';

        // create a list of addresses to ping
        $addresses = array(
            'localhost',
            '127.0.0.1',
            '::1'
        );

        // get the port that we'll be targeting and allow filtering
        $port = apply_filters('wpil_filter_varnish_purge_port', 6081);

        // if we have a port
        if(!empty($port) && is_numeric($port)){
            // add it to the host header
            $host . ':' . $port;
        }

		// go over the address list and ping each one
		foreach($addresses as $address) {

			// assemble the URL to ping
			$call_url = $protocol . $address . $path;

            // assemble the headers
            $headers = 	array(
                'sslverify' => false,
                'method'    => 'PURGE',
                'headers'   => array(
                    'host'           => $host,
                    'X-Purge-Method' => 'default',
                ),
            );

            // make the call
			wp_remote_request($call_url, $headers);
		}
    }

    /**
     * Triggers a post update after clearing the post cache to _hopefully_ get around caching issues.
     * Only focussing on clearing caches for posts, there doesn't seem to be much need on terms
     **/
    public static function trigger_clean_post_update($post_id, $type = 'post'){
        // exit if we're not supposed to be updating the post
        if(empty($post_id) || !Wpil_Settings::update_post_after_actions()){
            return;
        }

        if($type === 'post'){
            // delete the existing cache for this post
            wp_cache_delete($post_id, 'posts');
            // get a fresh version from the DB to make sure it exists
            $post = get_post($post_id);
            // if it does and there were no issues
            if(!empty($post) && !is_a($post, 'WP_Error')){
                // "update" the post
                wp_update_post(array(
                    'ID' => $post->ID
                ));
            }
        }
    }
}
