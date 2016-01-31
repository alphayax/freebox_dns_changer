<?php
namespace alphayax\utils;

/**
 * Class Cli
 * @package alphayax\utils
 * @author <alphayax@gmail.com>
 */
class Cli {

	/// Default Streams
	const STREAM_STDOUT = 'php://stdout';
	const STREAM_STDERR = 'php://stderr';

	/// Default Colors
	const COLOR_REGULAR = "%s";
	const COLOR_CYAN    = "\033[01;36m%s\033[00m";
	const COLOR_MAGENTA = "\033[01;35m%s\033[00m";
	const COLOR_BLUE    = "\033[01;34m%s\033[00m";
	const COLOR_YELLOW  = "\033[01;33m%s\033[00m";
	const COLOR_GREEN   = "\033[01;32m%s\033[00m";
	const COLOR_RED     = "\033[01;31m%s\033[00m";

	/**
	 * Write message on stdout
	 * @param string    $_msg           Message
	 * @param int       $_indent_nb     Indentation ( = 0)
	 * @param bool      $_eol           Add a Carriage return ( = true)
	 * @param string    $_color         One of constant Cli::COLOR_*
	 */
	public static function stdout( $_msg, $_indent_nb = 0, $_eol = true, $_color = self::COLOR_REGULAR){
		self::_stream_write( self::STREAM_STDOUT, $_msg, $_indent_nb, $_eol, $_color);
	}

	/**
	 * Write message on stderr
	 * @param string    $_msg           Message
	 * @param int       $_indent_nb     Indentation ( = 0)
	 * @param bool      $_eol           Add a Carriage return ( = true)
	 * @param string    $_color         One of constant Cli::COLOR_*
	 */
	public static function stderr( $_msg, $_indent_nb = 0, $_eol = true, $_color = self::COLOR_REGULAR){
		self::_stream_write( self::STREAM_STDERR, $_msg, $_indent_nb, $_eol, $_color);
	}

	/**
	 * Get a string given to stdin
	 * @return string
	 */
	public static function stdin(){
		return trim( fgets( STDIN));
	}

	/**
	 * Write to given stream
	 * @param string    $_stream        Filename or stream (php://*)
	 * @param string    $_msg           Message
	 * @param int       $_indent_nb     Indentation ( = 0)
	 * @param bool      $_eol           Add a Carriage return ( = true)
	 * @param string    $_color         One of constant Cli::COLOR_*
	 */
	private static function _stream_write( $_stream, $_msg, $_indent_nb, $_eol, $_color){
		$indent = $_indent_nb   ? str_repeat( '  ', $_indent_nb)    : '';
		$eol    = $_eol         ? PHP_EOL                           : '';
		$text   = sprintf( $_color, $indent . $_msg . $eol);
		file_put_contents( $_stream, $text);
	}

}
