<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */

namespace TVE\Reporting\EventFields;

use TVE\Reporting\Logs;

class Created extends Event_Field {

	public static function key(): string {
		return 'date';
	}

	public static function can_group_by(): bool {
		return true;
	}

	public static function get_query_select_field( $db_col ): string {

		global $reports_query;
		$min_max_date = Logs::get_instance()->get_min_max_date();
		/* check also the db to see in what date range we have the data */
		$min_date = empty( $min_max_date->min_date ) ? time() : strtotime( $min_max_date->min_date );
		$max_date = empty( $min_max_date->max_date ) ? time() : strtotime( $min_max_date->max_date );

		if ( empty( $reports_query['filters']['date']['from'] ) ) {
			$from = $min_date;
		} else {
			$from = max( $min_date, strtotime( $reports_query['filters']['date']['from'] ) );
		}

		if ( empty( $reports_query['filters']['date']['to'] ) ) {
			$to = $max_date;
		} else {
			$to = min( $max_date, strtotime( $reports_query['filters']['date']['to'] ) );
		}

		$days = ( $to - $from ) / DAY_IN_SECONDS;

		if ( $days > 30 * 12 * 10 ) {
			/* display years if we have at least 10 */
			$format = 'DATE_FORMAT(`created`, "%Y")';
		} elseif ( $days > 30 * 10 ) {
			/* display months if we have at least 10 */
			$format = 'DATE_FORMAT(`created`, "%M %Y")';
		} elseif ( $days > 7 * 10 ) {
			/* display weeks if we have at least 10 */
			$format = 'DATE_FORMAT(`created`, "%YW%u")';
		} else {
			$format = 'DATE(`created`)';
		}

		return "$format AS date";
	}

	public static function get_label( $singular = true ): string {
		return $singular ? 'Date' : 'Dates';
	}

	public static function format_value( $value ) {
		return strtotime( $value );
	}

	public function get_title(): string {
		return $this->value === null ? 'Date' : static::format_value( $this->value );
	}

	public static function get_filter_type(): string {
		return 'date-range-picker';
	}
}
