<?php

/**
 * This class handles the exports made with the "Export User Data (GDPR)" task of the Commerce -> Maintenance menu.
 */
class Daim_Export_Controller {

	public function __construct( $shared ) {

		//assign an instance of the plugin info
		$this->shared = $shared;

		//Export XML controller
		add_action( 'init', array( $this, 'export_csv_controller' ) );

		//Export XML controller
		add_action('init', array($this, 'export_xml_controller'));

	}

	/*
	 * The "Export CSV" buttons and/or icons available in the Dashboard, Juice
	 * and Hits menus are intercepted and the proper method that generates on
	 * the fly the specific downloadable CSV file is called
	 */
	public function export_csv_controller(){

		/*
		 * Intercept requests that come from the "Export CSV" button from the
		 * "Dashboard" menu and generate the downloadable CSV file with the
		 * dashboard_menu_export_csv() method
		 */
		if( isset($_GET['page']) and isset($_POST['export_csv']))
		{
			$page = sanitize_key($_GET['page']);
			if($page === 'daim-dashboard'){
				$this->dashboard_menu_export_csv();
			}
		}

		/*
		 * Intercept requests that come from the "Export CSV" button from the
		 * "Juice" menu and generate the downloadable CSV file with the
		 * juice_menu_export_csv() method
		 */
		if( isset($_GET['page']) and
		    isset($_POST['export_csv']))
		{
			$page = sanitize_key($_GET['page']);
			if($page === 'daim-juice'){
				$this->juice_menu_export_csv();
			}
		}

		/*
		 * Intercept requests that come from the download icon associated with the URLs of the "Juice" menu and generate
		 * the downloadable CSV file with the anchors_menu_export_csv() method.
		 */
		if( isset($_GET['page']) and isset($_POST['export_anchors_csv']) and isset($_POST['anchors_url']))
		{
			$page = sanitize_key($_GET['page']);
			if($page === 'daim-juice'){
				$this->anchors_menu_export_csv();
			}
		}

		/*
		 * Intercept requests that come from the "Export CSV" button from the
		 * "Hits" menu and generate the downloadable CSV file with the
		 * hits_menu_export_csv() method
		 */
		if( isset($_GET['page']) and
		    isset($_POST['export_csv']))
		{
			$page = sanitize_key($_GET['page']);
			if($page === 'daim-hits'){
				$this->hits_menu_export_csv();
			}
		}

		/*
		 * Intercept requests that come from the "Export CSV" button from the
		 * "Hits" menu and generate the downloadable CSV file with the
		 * hits_menu_export_csv() method
		 */
		if( isset($_GET['page']) and
		    isset($_POST['export_csv']))
		{
			$page = sanitize_key($_GET['page']);
			if($page === 'daim-http-status'){
				$this->http_status_menu_export_csv();
			}
		}

	}

	/*
	 * Generates the downloadable CSV file with all the items available in the
	 * Dashboard menu
	 */
	private function dashboard_menu_export_csv(){

		//verify capability
		if(!current_user_can(get_option( $this->shared->get('slug') . '_dashboard_menu_required_capability'))){die();}

		//Set the PHP "Max Execution Time" and "Memory Limit" based on the values defined in the options
		$this->shared->set_met_and_ml();

		//get the data from the db table
		global $wpdb;
		$table_name = $wpdb->prefix . $this->shared->get('slug') . "_archive";
		$results = $wpdb->get_results("SELECT * FROM $table_name ORDER BY post_date DESC", ARRAY_A);

		//if there are data generate the csv header and content
		if( count( $results ) > 0 ){

			$csv_content = '';
			$new_line = "\n";

			//set the csv header
			header('Content-Encoding: UTF-8');
			header('Content-type: text/csv; charset=UTF-8');
			header("Content-Disposition: attachment; filename=dashboard-" . time() . ".csv");
			header("Pragma: no-cache");
			header("Expires: 0");

			//set headings
			$csv_content .= '"' . $this->esc_csv(__('Post', 'daim')) . '",';
			$csv_content .= '"' . $this->esc_csv(__('Date', 'daim')) . '",';
			$csv_content .= '"' . $this->esc_csv(__('PT', 'daim')) . '",';
			$csv_content .= '"' . $this->esc_csv(__('CL', 'daim')) . '",';
			$csv_content .= '"' . $this->esc_csv(__('MIL', 'daim')) . '",';
			$csv_content .= '"' . $this->esc_csv(__('AIL', 'daim')) . '",';
			$csv_content .= '"' . $this->esc_csv(__('IIL', 'daim')) . '",';
			$csv_content .= '"' . $this->esc_csv(__('RI', 'daim')) . '",';
			$csv_content .= '"' . $this->esc_csv(__('VS', 'daim')) . '",';
			$csv_content .= '"' . $this->esc_csv(__('OF', 'daim')) . '"';
			$csv_content .= $new_line;

			//set column content
			foreach ( $results as $result ) {

				$csv_content .= '"' . $this->esc_csv($result['post_title']) . '",';
				$csv_content .= '"' . $this->esc_csv( mysql2date( get_option('date_format') , $result['post_date'] ) ) . '",';
				$csv_content .= '"' . $this->esc_csv($result['post_type']) . '",';
				$csv_content .= '"' . $this->esc_csv($result['content_length']) . '",';
				$csv_content .= '"' . $this->esc_csv($result['manual_interlinks']) . '",';
				$csv_content .= '"' . $this->esc_csv($result['auto_interlinks']) . '",';
				$csv_content .= '"' . $this->esc_csv($result['iil']) . '",';
				$csv_content .= '"' . $this->esc_csv($result['recommended_interlinks']) . '",';
				$csv_content .= '"' . $this->esc_csv($this->shared->get_number_of_hits($result['post_id'])) . '",';
				$csv_content .= '"' . $this->esc_csv($result['optimization']) . '"';
				$csv_content .= $new_line;

			}

		}else{
			return false;
		}

		echo $csv_content;
		die();

	}

	/*
	 * Generates the downloadable CSV file with all the items available in the
	 * Juice menu
	 */
	private function juice_menu_export_csv(){

		//verify capability
		if(!current_user_can(get_option( $this->shared->get('slug') . "_juice_menu_required_capability"))){die();}

		//Set the PHP "Max Execution Time" and "Memory Limit" based on the values defined in the options
		$this->shared->set_met_and_ml();

		//get the data from the db table
		global $wpdb;
		$table_name = $wpdb->prefix . $this->shared->get('slug') . "_juice";
		$results = $wpdb->get_results("SELECT * FROM $table_name ORDER BY juice DESC", ARRAY_A);

		//if there are data generate the csv header and content
		if( count( $results ) > 0 ){

			$csv_content = '';
			$new_line = "\n";

			//set the csv header
			header('Content-Encoding: UTF-8');
			header('Content-type: text/csv; charset=UTF-8');
			header("Content-Disposition: attachment; filename=juice-" . time() . ".csv");
			header("Pragma: no-cache");
			header("Expires: 0");

			//set headings
			$csv_content .= '"' . $this->esc_csv(__('URL', 'daim')) . '",';
			$csv_content .= '"' . $this->esc_csv(__('IIL', 'daim')) . '",';
			$csv_content .= '"' . $this->esc_csv(__('Juice (Value)', 'daim')) . '"';
			$csv_content .= $new_line;

			//set column content
			foreach ( $results as $result ) {

				$csv_content .= '"' . $this->esc_csv($result['url']) . '",';
				$csv_content .= '"' . $this->esc_csv($result['iil']) . '",';
				$csv_content .= '"' . $this->esc_csv($result['juice']) . '"';
				$csv_content .= $new_line;

			}

		}else{
			return false;
		}

		echo $csv_content;
		die();

	}

	/*
	 * Generates the downloadable CSV file with all the items associated with a
	 * specificd link available in the Juice menu
	 */
	private function anchors_menu_export_csv(){

		//verify capability
		if(!current_user_can(get_option( $this->shared->get('slug') . "_juice_menu_required_capability"))){die();}

		//Set the PHP "Max Execution Time" and "Memory Limit" based on the values defined in the options
		$this->shared->set_met_and_ml();

		//get the URL
		$url = esc_url_raw(urldecode($_POST['anchors_url']));

		//get the data from the db table
		global $wpdb;
		$table_name = $wpdb->prefix . $this->shared->get('slug') . "_anchors";
		$safe_sql = $wpdb->prepare("SELECT * FROM $table_name WHERE url = %s ORDER BY juice DESC", $url);
		$results = $wpdb->get_results($safe_sql, ARRAY_A);

		//if there are data generate the csv header and content
		if( count( $results ) > 0 ){

			$csv_content = '';
			$new_line = "\n";

			//set the csv header
			header('Content-Encoding: UTF-8');
			header('Content-type: text/csv; charset=UTF-8');
			header("Content-Disposition: attachment; filename=juice-details-" . time() . ".csv");
			header("Pragma: no-cache");
			header("Expires: 0");

			//set headings
			$csv_content .= '"' . $this->esc_csv(__('URL', 'daim')) . '",';
			$csv_content .= '"' . $this->esc_csv(__('Post', 'daim')) . '",';
			$csv_content .= '"' . $this->esc_csv(__('Anchor Text', 'daim')) . '",';
			$csv_content .= '"' . $this->esc_csv(__('Juice', 'daim')) . '"';

			$csv_content .= $new_line;

			//set column content
			foreach ( $results as $result ) {

				$csv_content .= '"' . $this->esc_csv($result['url']) . '",';
				$csv_content .= '"' . $this->esc_csv($result['post_title']) . '",';
				$csv_content .= '"' . $this->esc_csv($result['anchor']) . '",';
				$csv_content .= '"' . $this->esc_csv($result['juice']) . '"';

				$csv_content .= $new_line;

			}

		}else{
			return false;
		}

		echo $csv_content;
		die();

	}

	/*
	 * Generates the downloadable CSV file with all the items available in the
	 * Hits menu
	 */
	private function hits_menu_export_csv(){

		//verify capability
		if(!current_user_can(get_option( $this->shared->get('slug') . "_hits_menu_required_capability"))){die();}

		//Set the PHP "Max Execution Time" and "Memory Limit" based on the values defined in the options
		$this->shared->set_met_and_ml();

		//get the data from the db table
		global $wpdb;
		$table_name = $wpdb->prefix . $this->shared->get('slug') . "_hits";
		$results = $wpdb->get_results("SELECT * FROM $table_name ORDER BY date DESC", ARRAY_A);

		//if there are data generate the csv header and content
		if( count( $results ) >0 ){

			$csv_content = '';
			$new_line = "\n";

			//set the csv header
			header('Content-Encoding: UTF-8');
			header('Content-type: text/csv; charset=UTF-8');
			header("Content-Disposition: attachment; filename=hits-" . time() . ".csv");
			header("Pragma: no-cache");
			header("Expires: 0");

			//set headings
			$csv_content .= '"' . $this->esc_csv(__('Post', 'daim')) . '",';
			$csv_content .= '"' . $this->esc_csv(__('Date', 'daim')) . '",';
			$csv_content .= '"' . $this->esc_csv(__('Target', 'daim')) . '",';
			$csv_content .= '"' . $this->esc_csv(__('Type', 'daim')) . '"';
			$csv_content .= $new_line;

			//set column content
			foreach ( $results as $result ) {

				$csv_content .= '"' . $this->esc_csv($result['post_title']) . '",';
				$csv_content .= '"' . $this->esc_csv(mysql2date( get_option('date_format') , $result['date'] )) . '",';
				$csv_content .= '"' . $this->esc_csv(stripslashes($result['target_url'])) . '",';
				$csv_content .= '"' . $this->esc_csv( $result['link_type'] == 0 ? 'AIL' : 'MIL' ) . '"';
				$csv_content .= $new_line;

			}

		}else{
			return false;
		}

		echo $csv_content;
		die();

	}

	/*
     * Generates the downloadable CSV file with all the items available in the HTTP Status menu.
     */
	private function http_status_menu_export_csv(){

		//verify capability
		if(!current_user_can(get_option( $this->shared->get('slug') . "_http_status_menu_required_capability"))){die();}

		//Set the PHP "Max Execution Time" and "Memory Limit" based on the values defined in the options
		$this->shared->set_met_and_ml();

		//get the data from the db table
		global $wpdb;
		$table_name = $wpdb->prefix . $this->shared->get('slug') . "_http_status";
		$results = $wpdb->get_results("SELECT * FROM $table_name ORDER BY last_check_date DESC", ARRAY_A);

		//if there are data generate the csv header and content
		if( count( $results ) >0 ){

			$csv_content = '';
			$new_line = "\n";

			//set the csv header
			header('Content-Encoding: UTF-8');
			header('Content-type: text/csv; charset=UTF-8');
			header("Content-Disposition: attachment; filename=http-response-" . time() . ".csv");
			header("Pragma: no-cache");
			header("Expires: 0");

			//set headings
			$csv_content .= '"' . $this->esc_csv(__('Post', 'daim')) . '",';
			$csv_content .= '"' . $this->esc_csv(__('Anchor Text', 'daim')) . '",';
			$csv_content .= '"' . $this->esc_csv(__('URL', 'daim')) . '",';
			$csv_content .= '"' . $this->esc_csv(__('Status Code', 'daim')) . '",';
			$csv_content .= '"' . $this->esc_csv(__('Last Check', 'daim')) . '"';
			$csv_content .= $new_line;

			//set column content
			foreach ( $results as $result ) {

				$csv_content .= '"' . $this->esc_csv($result['post_title']) . '",';
				$csv_content .= '"' . $this->esc_csv($result['anchor']) . '",';
				$csv_content .= '"' . $this->esc_csv($result['url']) . '",';
				$csv_content .= '"' . $this->esc_csv($result['code'] . ' ' . $this->shared->get_status_code_description($result['code'])) . '",';
				$csv_content .= '"' . $this->esc_csv(mysql2date( get_option('date_format') , $result['last_check_date'] )) . '",';
				$csv_content .= $new_line;

			}

		}else{
			return false;
		}

		echo $csv_content;
		die();

	}

	/*
	 * The click on the "Export" button available in the "Export" menu is intercepted and the method that generates the
	 * downloadable XML file is called.
	 */
	public function export_xml_controller()
	{

		/*
		 * Intercept requests that come from the "Export" button of the "Autolinks Manager -> Export" menu and generate
		 * the downloadable XML file.
		 */
		if (isset($_POST['daim_export'])) {

			//verify capability
			if ( ! current_user_can(get_option($this->shared->get('slug') . "_export_menu_required_capability"))) {
				wp_die(esc_html__('You do not have sufficient permissions to access this page.'));
			}

			//generate the header of the XML file
			header('Content-Encoding: UTF-8');
			header('Content-type: text/xml; charset=UTF-8');
			header("Content-Disposition: attachment; filename=interlinks-manager-" . time() . ".xml");
			header("Pragma: no-cache");
			header("Expires: 0");

			//generate initial part of the XML file
			$out = '<?xml version="1.0" encoding="UTF-8" ?>';
			$out .= '<root>';

			//Generate the XML of the various db tables
			$out .= $this->shared->convert_db_table_to_xml('autolinks', 'id');
			$out .= $this->shared->convert_db_table_to_xml('category', 'category_id');
			$out .= $this->shared->convert_db_table_to_xml('term_group', 'term_group_id');

			//generate the final part of the XML file
			$out .= '</root>';

			echo $out;
			die();

		}

	}

	/*
	 * Escape the double quotes of the $content string, so the returned string
	 * can be used in CSV fields enclosed by double quotes
	 *
	 * @param $content The unescape content ( Ex: She said "No!" )
	 * @return string The escaped content ( Ex: She said ""No!"" )
	 */
	private function esc_csv($content){
		return str_replace('"', '""', $content);
	}

}