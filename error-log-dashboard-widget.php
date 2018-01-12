<?php

/*
Plugin Name: Error Log Dashboard Widget
Plugin URI: https://github.com/Rarst/error-log-dashboard-widget
Description: Robust zero-configuration and low-memory WordPress plugin to keep an eye on error log.
Author: Andrey "Rarst" Savchenko
Author URI: http://www.rarst.net/
Version:
License: GPLv2 or later

Includes last_lines() function by phant0m, licensed under cc-wiki and GPLv2+
*/

Error_Log_Dashboard_Widget::on_load();

/**
 * Main plugin's class.
 */
class Error_Log_Dashboard_Widget {

	/**
	 * Set up logic on load.
	 */
	public static function on_load() {

		add_action( 'admin_init', array( __CLASS__, 'admin_init' ) );
	}

	/**
	 * Set up logic on admin init.
	 */
	public static function admin_init() {

		add_action( 'wp_dashboard_setup', array( __CLASS__, 'wp_dashboard_setup' ) );
	}

	/**
	 * Add dashboard widget.
	 */
	public static function wp_dashboard_setup() {

		if ( current_user_can( apply_filters( 'error_log_widget_capability', 'manage_options' ) ) ) {
			wp_add_dashboard_widget( 'error-log-widget', __( 'Error Log', 'error-log-widget' ), array( __CLASS__, 'widget_callback' ) );
		}
	}

	/**
	 * Read log and render widget output.
	 */
	public static function widget_callback() {

		$log_errors = ini_get( 'log_errors' );

		if ( ! $log_errors ) {
			echo '<p>' . __( 'Error logging disabled.', 'error-log-widget' ) . ' <a href="http://codex.wordpress.org/Editing_wp-config.php#Configure_Error_Log">' . __( 'Configure error log', 'error-log-widget' ) . '</a></p>';
		}

		$error_log = ini_get( 'error_log' );
		$logs      = apply_filters( 'error_log_widget_logs', array( $error_log ) );
		$count     = apply_filters( 'error_log_widget_lines', 10 );
		$lines     = array();

		foreach ( $logs as $log ) {

			if ( is_readable( $log ) ) {
				$lines = array_merge( $lines, self::last_lines( $log, $count ) );
			}
		}

		$lines = array_map( 'trim', $lines );
		$lines = array_filter( $lines );

		if ( empty( $lines ) ) {

			echo '<p>' . __( 'No errors found... Yet.', 'error-log-widget' ) . '</p>';

			return;
		}

		foreach ( $lines as $key => $line ) {

			if ( false !== strpos( $line, ']' ) ) {
				list( $time, $error ) = explode( ']', $line, 2 );
			} else {
				list( $time, $error ) = array( '', $line );
			}

			$time          = trim( $time, '[]' );
			$error         = trim( $error );
			$lines[ $key ] = compact( 'time', 'error' );
		}

		if ( count( $error_log ) > 1 ) {

			uasort( $lines, array( __CLASS__, 'time_field_compare' ) );
			$lines = array_slice( $lines, 0, $count );
		}

		echo '<table class="widefat">';

		foreach ( $lines as $line ) {

			$error = esc_html( $line['error'] );
			$time  = esc_html( $line['time'] );

			if ( ! empty( $error ) ) {
				echo( "<tr><td>{$time}</td><td>{$error}</td></tr>" );
			}
		}

		echo '</table>';
	}

	/**
	 * Compare callback for freeform date/time strings.
	 *
	 * @param string $a First value.
	 * @param string $b Second value.
	 *
	 * @return int
	 */
	public static function time_field_compare( $a, $b ) {

		if ( $a == $b ) {
			return 0;
		}

		return ( strtotime( $a['time'] ) > strtotime( $b['time'] ) ) ? - 1 : 1;
	}

	/**
	 * Reads lines from end of file. Memory-safe.
	 *
	 * @link http://stackoverflow.com/questions/6451232/php-reading-large-files-from-end/6451391#6451391
	 *
	 * @param string  $path       Filesystem path to the file.
	 * @param integer $line_count How many lines to read.
	 * @param integer $block_size Size of block to use for read.
	 *
	 * @return array
	 */
	public static function last_lines( $path, $line_count, $block_size = 512 ) {
		$lines = array();

		// we will always have a fragment of a non-complete line
		// keep this in here till we have our next entire line.
		$leftover = '';

		$fh = fopen( $path, 'r' );
		// go to the end of the file.
		fseek( $fh, 0, SEEK_END );

		do {
			// need to know whether we can actually go back
			// $block_size bytes.
			$can_read = $block_size;

			if ( ftell( $fh ) <= $block_size ) {
				$can_read = ftell( $fh );
			}

			if ( empty( $can_read ) ) {
				break;
			}

			// go back as many bytes as we can
			// read them to $data and then move the file pointer
			// back to where we were.
			fseek( $fh, - $can_read, SEEK_CUR );
			$data  = fread( $fh, $can_read );
			$data .= $leftover;
			fseek( $fh, - $can_read, SEEK_CUR );

			// split lines by \n. Then reverse them,
			// now the last line is most likely not a complete
			// line which is why we do not directly add it, but
			// append it to the data read the next time.
			$split_data = array_reverse( explode( "\n", $data ) );
			$new_lines  = array_slice( $split_data, 0, - 1 );
			$lines      = array_merge( $lines, $new_lines );
			$leftover   = $split_data[ count( $split_data ) - 1 ];
		} while ( count( $lines ) < $line_count && ftell( $fh ) != 0 );

		if ( ftell( $fh ) == 0 ) {
			$lines[] = $leftover;
		}

		fclose( $fh );
		// Usually, we will read too many lines, correct that here.
		return array_slice( $lines, 0, $line_count );
	}
}
