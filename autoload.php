<?php
namespace alphayax;


/**
 * Class AYX_Autoloader
 * @author Yann Ponzoni <alphayax@gmail.com>
 */
final class AYX_Autoloader {

	const SOURCE_ADI        = __DIR__;          // Directory where the sources are
	const ROOT_NAMESPACE    = __NAMESPACE__;    // Name of the root namespace
	const DEBUG_AUTOLOAD    = true;            // Enable or disable advanced logging when autoload fail

	/**
	* Add the PublishingLib autoloader in the autoloaders stack
	*/
	final public static function Register(){
		spl_autoload_register( [ __CLASS__, 'Load'], true, true);
	}

	/**
	* Autoload method
	* @param   string  $_class Full name of the class to load
	* @return  boolean         True if the class have been successfully loader
	*/
	final public static function Load( $_class){
		$namespace  = explode( '\\', $_class);  // Split the full classname in namespaces parts + class name
		$class      = array_pop( $namespace);   // Extract the class name from the namespaces parts
		/// We only autoload the "alphayax" namespace
		if( self::ROOT_NAMESPACE === @$namespace[0]){
			return self::Autoload_from_namespace( $namespace, $class);
		}
		return false;
	}

	/**
	* Autoload a class switch it's namespace
	* @example class a\b\c will be load from file "a/b/c.class.php"
	* @param array $_namespaces
	* @param string $_class
	* @return bool
	*/
	final private static function Autoload_from_namespace( $_namespaces, $_class){
		array_shift( $_namespaces);                                 // Removing the root namespace (Publishing)
		$class_rdi = implode( DIRECTORY_SEPARATOR, $_namespaces);   // Concat all NS with a /
		$class_rdi = $class_rdi ? "$class_rdi/" : '';               // Adding the / between NS and class name

		/// In production mode, we simply require the file
		if( ! self::DEBUG_AUTOLOAD){
			require_once $class_rdi . "$_class.php";          // Load the specified PHP file
			return true;
		}

		/// In debug mode, we check if the file we want to autoload exists
		trigger_error("Load: ". self::SOURCE_ADI .'/'. $class_rdi . "$_class.php", E_USER_NOTICE);
		if( is_file( self::SOURCE_ADI .'/'. $class_rdi . "$_class.php")){
			require_once $class_rdi . "$_class.php";              // Load the specified PHP file
			return true;
		}

		/// Here, we are in debug mode and we don't find the file to load.
		trigger_error( "ERROR !!! Class not autoloaded properly [$_class]");
		$i = 0;
		foreach( debug_backtrace() as $lNode) {
			trigger_error( ++$i .' '.basename(@$lNode['file']) ." : " .@$lNode['function'] ."(" .@$lNode['line'].")");
		}
		return false;
	}

}

