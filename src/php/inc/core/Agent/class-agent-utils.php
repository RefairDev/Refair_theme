<?php
/**
 *  File containing Agent_Utils class.
 *
 * @package Pixelscodex
 */

namespace Pixelscodex;

/**
 * Class grouping all the utility functions of the core of the theme.
 */
class Agent_Utils {

	/**
	 * Extend require function to do it recursively.
	 *
	 * @param  string $dir Directory to scan.
	 * @param  array  $exclude Files excluded.
	 * @return array Files required after recursif scan.
	 */
	public static function require( $dir, $exclude = array() ) {
			$files_returned = array();
			$files          = array_diff( scandir( $dir, 1 ), array( '.', '..', 'index.php' ) );

		foreach ( $files as $file ) {
			if ( ! in_array( $file, $exclude, true ) && ! in_array( basename( $file ), $exclude, true ) ) {
				if ( ! is_dir( $file ) ) {
					$returned         = require_once $dir . '/' . $file;
					$files_returned[] = $dir . '/' . $file;
				} else {
					self::require( $dir . '/' . $file, $exclude = array() );
				}
			}
		}
			return $files_returned;
	}

	/**
	 * Convert string to UTF-8.
	 *
	 * @param  string $string_to_convert Original string to convert.
	 * @return string string converted to UTF-8.
	 */
	public static function convert_string( $string_to_convert ) {
		static $use_mb = null;

		if ( is_null( $use_mb ) ) {
			$use_mb = function_exists( 'mb_convert_encoding' );
		}

		if ( $use_mb ) {
			$encoding = mb_detect_encoding( $string_to_convert, mb_detect_order(), true );
			if ( $encoding ) {
				return mb_convert_encoding( $string_to_convert, 'UTF-8', $encoding );
			} else {
				return mb_convert_encoding( $string_to_convert, 'UTF-8', 'UTF-8' );
			}
		} else {
			return $string_to_convert;
		}
	}

	/**
	 * Write content in file.
	 *
	 * @param  string $file filename.
	 * @param  string $content Content to write in the file.
	 * @return void
	 *
	 * @throws Exception
	 */
	public static function fwrite( $file, $content ) {
		$fd = fopen( $file, 'w' );

		$content_length = strlen( $content );

		if ( false === $fd ) {
			throw new Exception( 'File not writable.', 1 );
		}

		$written = fwrite( $fd, $content );

		fclose( $fd );

		if ( false === $written ) {
			throw new Exception( 'Unable to write to file.', 1 );
		}
	}

	/**
	 * Get php class(es) of a file
	 *
	 * @param  string $filepath path to the file to check.
	 * @return array class(es) name(s) in the file.
	 */
	public static function file_get_php_classes( $filepath ) {
		$php_code = file_get_contents( $filepath );
		$classes  = self::get_php_classes( $php_code );
		return $classes;
	}

	/**
	 * Extract class(es) name from php code.
	 *
	 * @param  string $php_code Input php code scan.
	 * @return array class(es) name(s) found in code.
	 */
	public static function get_php_classes( $php_code ) {
		$classes = array();
		$tokens  = token_get_all( $php_code );
		$count   = count( $tokens );
		for ( $i = 2; $i < $count; $i++ ) {
			if ( T_CLASS === $tokens[ $i - 2 ][0]
				&& T_WHITESPACE === $tokens[ $i - 1 ][0]
				&& T_STRING === $tokens[ $i ][0] ) {

				$class_name = $tokens[ $i ][1];
				$classes[]  = $class_name;
			}
		}
		return $classes;
	}

	/**
	 * Change string from dash sperator to Capitalized Character.
	 *
	 * @param  string  $string_to_convert String to convert.
	 * @param  boolean $capitalize_first_character Does first first character have to be capitalized.
	 * @return string String converted.
	 */
	public static function dash_to_camel_case( $string_to_convert, $capitalize_first_character = false ) {
		$str = str_replace( '-', '', ucwords( $string_to_convert, '-' ) );
		if ( ! $capitalize_first_character ) {
			$str = lcfirst( $str );
		}
		return $str;
	}
}
