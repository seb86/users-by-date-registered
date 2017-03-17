<?php
/**
 * Plugin Name: Users by Date Registered
 * Plugin URI: https://wordpress.org/plugins/users-by-date-registered
 * Description: Displays a new column displaying the date the user registered and allows you to filter the users by date.
 * Version: 1.0.2
 * Author: Sébastien Dumont
 * Author URI: http://www.sebastiendumont.com
 * Requires at least: 4.5
 * Tested up to: 4.7.3
 *
 * Text Domain: users-by-date-registered
 * Domain Path: languages
 *
 * Copyright: (c) 2014-2017 Sébastien Dumont. (mailme@sebastiendumont.com)
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Adds a new column to the users table for 'Date Registered'.
 *
 * @since 1.0.0
 */
function add_user_table_columns( $column ) {
  $column['registered'] = __( 'Date Registered', 'users-by-date-registered' );

  return $column;
}
add_filter( 'manage_users_columns', 'add_user_table_columns' );

/**
 * Filters the users table columns to display the
 * date and time the users registered.
 *
 * @since 1.0.0
 */
function modify_user_table_row( $val, $column_name, $user_id ) {
  $user = get_userdata( $user_id );

  switch( $column_name ) {
    case 'registered' :
      $t_time    = get_the_time( __( 'Y/m/d g:i:s A' ) );
      $m_time    = $user->user_registered;
      $time      = get_post_time( 'G', true, $user );
      $time_diff = time() - $time;

      if ( $time_diff > 0 && $time_diff < DAY_IN_SECONDS ) {
        $h_time = sprintf( __( '%s ago' ), human_time_diff( $time ) );
      }
      else {
        $h_time = mysql2date( __( 'd/m/Y' ), $m_time );
      }

      return '<abbr title="' . $t_time . '">' . apply_filters( 'user_registered_date_column_time', $h_time, $user, $column_name ) . '</abbr>';

      break;

    default:
      break;
  }
}
add_filter( 'manage_users_custom_column', 'modify_user_table_row', 10, 3 );

/**
 * Allows you to sort the users table by date order.
 *
 * @since 1.0.0
 */
function get_sortable_columns() {
	return array(
		'registered' => array( 'registered', true )
	);
}
add_filter( 'manage_users_sortable_columns', 'get_sortable_columns' );

/**
 * Display a monthly dropdown for filtering the users.
 * Forked from file: class-wp-list-table.php
 *
 * @since 1.0.0
 */
function users_months_dropdown() {
	global $wpdb, $wp_locale;

	// Bail if current user cannot manage options
	if ( !current_user_can( 'manage_options' ) )
		return;

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

	if ( !$month_count || ( 1 == $month_count && 0 == $months[0]->month ) )
		return;

  if ( isset( $_GET[ 'userregistered' ]) ) {
    $m = (int) $_GET[ 'userregistered' ];
    $m = !empty( $m[ 0 ] ) ? $m[ 0 ] : $m[ 1 ];
  } else {
    $m = (int) 0;
  }
	?>
	<label for="filter-users-by-date" class="screen-reader-text"><?php _e( 'Filter users by date', 'users-by-date-registered' ); ?></label>
		<select name="userregistered[]" id="filter-users-by-date" style="display:inline-block; float:none;">
			<option<?php selected( $m, "0" ); ?> value="0"><?php _e( 'All dates', 'users-by-date-registered' ); ?></option>
			<?php
			foreach ( $months as $arc_row ) {
				if ( 0 == $arc_row->year )
					continue;

				$month = zeroise( $arc_row->month, 2 );
				$year  = $arc_row->year;

				printf( "<option %s value='%s'>%s</option>\n",
					selected( $m, $year . $month, false ),
					esc_attr( $arc_row->year . $month ),
					/* translators: 1: month name, 2: 4-digit year */
					sprintf( __( '%1$s %2$d' ), $wp_locale->get_month( $month ), $year )
				);
			}
			?>
		</select>
<?php
		submit_button( __( 'Filter By Date', 'users-by-date-registered' ), 'secondary', 'filterdate', false );
}
add_action( 'restrict_manage_users', 'users_months_dropdown' );

/**
 * This filters the users table by date.
 *
 * @since 1.0.0
 */
function extended_user_search( $user_query ){
	global $pagenow, $wpdb;

  if ( is_admin() && 'users.php' == $pagenow && isset( $_GET[ 'userregistered' ] ) && is_array( $_GET[ 'userregistered' ] ) ) {
    $date  = $_GET[ 'userregistered' ];
    $date  = !empty( $date[ 0 ] ) ? $date[ 0 ] : $date[ 1 ];

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

}
add_action( 'pre_user_query', 'extended_user_search' );
