<?php
/**
 * Plugin Name: Users by Date Registered
 * Plugin URI:  https://wordpress.org/plugins/users-by-date-registered
 * Description: Allows you to see the dates users registered and filter the users by date.
 * Version:     1.0.7
 * Author:      Sébastien Dumont
 * Author URI:  https://sebastiendumont.com
 * Text Domain: users-by-date-registered
 * Domain Path: /languages/
 *
 * Copyright:   © 2019 Sébastien Dumont.
 * License:     GNU General Public License v2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined('ABSPATH') ) {
	exit;
}

/**
 * Adds a new column to the users table for 'Date Registered'.
 *
 * @since  1.0.0
 * @param  array $column
 * @return array
 */
function sd_add_user_table_columns( $column ) {
	$column['registered'] = __( 'Date Registered', 'users-by-date-registered' );

	return $column;
} // sd_add_user_table_columns()
add_filter( 'manage_users_columns', 'sd_add_user_table_columns' );

/**
 * Filters the users table columns to display the
 * date and time the users registered.
 *
 * @since   1.0.0
 * @version 1.0.7
 * @param   $val
 * @param   array  $column_name
 * @param   object $user_id
 * @return  string
 */
function sd_modify_user_table_row( $val, $column_name, $user_id ) {
	$user = get_userdata( $user_id );

	if ( $column_name === 'registered' ) {
		$t_time    = get_the_time( __( 'Y/m/d g:i:s a' ) );
		$m_time    = $user->user_registered;
		$time      = mysql2date( 'G', $m_time, false );

		$time_diff = time() - $time;

		// Get the last 7 days.
		$days = ( intval( $time_diff ) / DAY_IN_SECONDS ) % 8;
		$days = $days * -1; // Convert negative value to positive.

		if ( $days > 0 && $days < 8 ) {
			/* translators: Number of days */
			$h_time = sprintf( __( '%s days ago', 'users-by-date-registered' ), $days );
		}
		else {
			$h_time = mysql2date( get_option( 'date_format' ), $m_time );
		}

		return '<abbr title="' . $t_time . '">' . apply_filters( 'user_registered_date_column_time', $h_time, $user ) . '</abbr>';
	}
} // END sd_modify_user_table_row()
add_filter( 'manage_users_custom_column', 'sd_modify_user_table_row', 10, 3 );

/**
 * Allows you to sort the users table by date order.
 *
 * @since   1.0.0
 * @version 1.0.7
 * @param   array $columns
 * @return  array
 */
function sd_get_sortable_columns( $columns ) {
	return wp_parse_args( array( 'registered' => 'registered' ), $columns );
} // END sd_get_sortable_columns()
add_filter( 'manage_users_sortable_columns', 'sd_get_sortable_columns' );

/**
 * Display a monthly dropdown for filtering the users.
 *
 * Forked from file: class-wp-list-table.php
 *
 * @since   1.0.0
 * @version 1.0.7
 * @param   string $which
 * @global  object $wpdb
 * @global  object $wp_locale
 */
function sd_users_months_dropdown( $which ) {
	global $wpdb, $wp_locale;

	// Bail if current user cannot manage options
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$userregistered = 'bottom' === $which ? 'userregistered2' : 'userregistered';
	$button_id      = 'bottom' === $which ? 'filterdate2' : 'filterdate';

	$months = $wpdb->get_results( "
		SELECT DISTINCT YEAR( user_registered ) AS year, MONTH( user_registered ) AS month
		FROM $wpdb->users ORDER BY user_registered DESC" );

	/**
	 * Filter the 'Months' drop-down results.
	 *
	 * @param object $months The months drop-down query results.
	 */
	$months = apply_filters( 'users_months_dropdown_results', $months );

	$month_count = count( $months );

	if ( ! $month_count || ( 1 == $month_count && 0 == $months[0]->month ) ) {
		return;
	}

	if ( ! empty( $_GET['userregistered'] ) ) {
		$filtered_date = $_GET['userregistered'];
	} elseif ( ! empty( $_GET['userregistered2'] ) ) {
		$filtered_date = $_GET['userregistered2'];
	}

	// Set as all dates if not filtered.
	if ( empty( $_GET['userregistered'] ) && empty( $_GET['userregistered2'] ) ) {
		$filtered_date = (int) 0;
	}
	?>
	<label class="screen-reader-text" for="<?php echo $userregistered; ?>"><?php _e( 'Filter users by date', 'users-by-date-registered' ); ?></label>
	<select name="<?php echo $userregistered; ?>" id="<?php echo $userregistered; ?>" style="float:none;">
		<option value="0"><?php _e( 'All dates', 'users-by-date-registered' ); ?></option>
		<?php
		foreach ( $months as $arc_row ) {
			if ( 0 == $arc_row->year )
				continue;

			$month = zeroise( $arc_row->month, 2 );
			$year  = $arc_row->year;

			printf( "<option value='%s'>%s</option>\n",
				esc_attr( $arc_row->year . $month ),
				sprintf( '%1$s %2$d', $wp_locale->get_month( $month ), $year )
			);
		}
		?>
	</select>
	<?php
	submit_button( __( 'Filter By Date', 'users-by-date-registered' ), '', $button_id, false );

	// Displays the date the users are filtered by.
	if ( ! empty( $filtered_date ) ) {
		$month = substr( $filtered_date, 4 );
		$month = zeroise( $month, 2 );
		$year  = substr( $filtered_date, 0, 4 );

		echo ' ';
		echo sprintf( __( 'Filtered by: <strong>%s</strong>', 'users-by-date-registered' ),
			sprintf( '%1$s %2$d', $wp_locale->get_month( $month ), $year )
		);
	}

} // END sd_users_months_dropdown()
add_action( 'restrict_manage_users', 'sd_users_months_dropdown' );

/**
 * This filters the users table by date.
 *
 * @since   1.0.0
 * @version 1.0.7
 * @param   $user_query
 * @global  string $pagenow
 * @global  object $wpdb
 */
function sd_extended_user_search( $user_query ) {
	global $pagenow, $wpdb;

	if ( ! is_admin() ) {
		return;
	}

	if ( 'users.php' !== $pagenow ) {
		return;
	}

	if ( isset( $_GET['userregistered'] ) ) {
		$date = $_GET['userregistered'];
	}
	elseif ( isset( $_GET['userregistered2'] ) ) {
		$date = $_GET['userregistered2'];
	}

	if ( ! empty( $date ) && is_numeric( $date ) ) {
		$year  = substr( $date, 0, -2 );
		$month = substr( $date, -2, 5 );

		switch( $month ) {
			case "01":
				$last_day = 31;
			break;
			case "02":
				$last_day = apply_filters( 'users_by_date_month_feb_last_day', 28 );
			break;
			case "03":
				$last_day = 31;
			break;
			case "04":
				$last_day = 30;
			break;
			case "05":
				$last_day = 31;
			break;
			case "06":
				$last_day = 30;
			break;
			case "07":
				$last_day = 31;
			break;
			case "08":
				$last_day = 31;
			break;
			case "09":
				$last_day = 30;
			break;
			case "10":
				$last_day = 31;
			break;
			case "11":
				$last_day = 30;
			break;
			case "12":
				$last_day = 31;
			break;
		}

		$user_query->query_where .= " AND user_registered >= '" . $year . "-" . $month . "-01 00:00:00' AND user_registered <= '" . $year . "-" . $month . "-" . $last_day . " 23:59:59'";
	}

} // END sd_extended_user_search()
add_action( 'pre_user_query', 'sd_extended_user_search' );
