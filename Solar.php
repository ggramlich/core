<?php
/**
 * 
 * Solar: Simple Object Library and Application repository for PHP5.
 * 
 * @category Solar
 * 
 * @package Solar
 * 
 * @author Paul M. Jones <pmjones@solarphp.net>
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 * Copyright (c) 2005-2006, Paul M. Jones.  All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 * 
 * * Redistributions of source code must retain the above copyright
 *   notice, this list of conditions and the following disclaimer.
 * 
 * * Redistributions in binary form must reproduce the above
 *   copyright notice, this list of conditions and the following
 *   disclaimer in the documentation and/or other materials provided
 *   with the distribution.
 * 
 * * Neither the name of the Solar project nor the names of its
 *   contributors may be used to endorse or promote products derived
 *   from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 * 
 * @version $Id$
 * 
 */

/**
 * Define where the Solar.config.php file is located.
 */
if (! defined('SOLAR_CONFIG_PATH')) {
    define('SOLAR_CONFIG_PATH', $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'Solar.config.php');
}

/**
 * Make sure Solar_Base is loaded even before Solar::start() is called.
 */
if (! class_exists('Solar_Base')) {
    require dirname(__FILE__) . DIRECTORY_SEPARATOR
          . 'Solar' . DIRECTORY_SEPARATOR . 'Base.php';
}

/**
 * 
 * The Solar arch-class provides static methods needed throughout the Solar environment.
 * 
 * @category Solar
 * 
 * @package Solar
 * 
 * @version @package_version@
 * 
 */
class Solar {
    
    
    /**
     * 
     * The values read in from the configuration file.
     * 
     * @var array
     * 
     */
    public static $config = array();
    
    /**
     * 
     * Where this class (the Solar arch-class) is in the filesystem.
     * 
     * @var string
     * 
     */
    public static $dir = null;
    
    /**
     * 
     * Object registry.
     * 
     * Objects are registered using [[Solar::register()]]; the registry
     * array is keyed on the name of the registered object.
     * 
     * Although this property is public, you generally shouldn't need
     * to manipulate it in any way.
     * 
     * @var array
     * 
     */
    public static $registry = array();
    
    /**
     * 
     * Status flag (whether Solar has started or not).
     * 
     * @var bool
     * 
     */
    protected static $_status = false;
    
    /**
     * 
     * Locale strings for all classes.
     * 
     * This is where locale strings for Solar are kept.  The array
     * is keyed first by the class name, and the sub-keys are the
     * translation keys.
     * 
     * Although this property is public, you generally shouldn't need
     * to manipulate it in any way.
     * 
     * @var array
     * 
     */
    public static $locale = array();
    
    /**
     * 
     * Parent hierarchy for all classes.
     * 
     * We keep track of this so configs, locale strings, etc. can be
     * inherited properly from parent classes.
     * 
     * Although this property is public, you generally shouldn't need
     * to manipulate it in any way.
     * 
     * @var array
     * 
     */
    public static $parents = array();
    
    /**
     * 
     * The current locale code being used by Solar.
     * 
     * Default value is 'en_US'.
     * 
     * @var string 
     * 
     * @todo Keep the locale code in a session variable?
     * 
     */
    protected static $_locale_code = 'en_US';
    
    /**
     * 
     * Constructor is disabled to enforce a singleton pattern.
     * 
     */
    final private function __construct() {}
    
    /**
     * 
     * Starts Solar: loads configuration values and and sets up the environment.
     * 
     * @param mixed $config An alternative configuration parameter.
     * 
     * @return void
     * 
     * @see Solar::cleanGlobals()
     * 
     * @see Solar::fetchConfig()
     * 
     */
    public static function start($config = null)
    {
        // don't re-start if we're already running.
        if (Solar::$_status) {
            return;
        }
        
        // where is Solar in the filesystem?
        Solar::$dir = dirname(__FILE__);
        
        // clear out registered globals
        if (ini_get('register_globals')) {
            Solar::cleanGlobals();
        }
        
        // fetch config values from file or other source
        Solar::$config = Solar::fetchConfig($config);
        
        // process ini settings from config file
        $settings = Solar::config('Solar', 'ini_set', array());
        foreach ($settings as $key => $val) {
            ini_set($key, $val);
        }
        
        // load the initial locale strings
        if (! empty(Solar::$config['locale_code'])) {
            Solar::$_locale_code = Solar::$config['locale_code'];
        }
        Solar::setLocale(Solar::$_locale_code);
        
        // run any 'start' hook scripts
        foreach ((array) Solar::config('Solar', 'start') as $file) {
            Solar::run($file);
        }
        
        // and we're done!
        Solar::$_status = true;
    }
    
    /**
     * 
     * Stops Solar: run stop scripts.
     * 
     * @return void
     * 
     */
    public static function stop()
    {
        // run the user-defined stop scripts.
        foreach ((array) Solar::config('Solar', 'stop') as $file) {
            Solar::run($file);
        }
        
        // reset the status flag, and we're done.
        Solar::$_status = false;
    }
    
    /**
     * 
     * Returns the API version for Solar.
     * 
     * @return string A PHP-standard version number.
     * 
     */
    public static function apiVersion()
    {
        return '@package_version@';
    }
    
    /**
     * 
     * Gets the translated locale string for a class and key.
     * 
     * Loads locale string files on-demand.
     * 
     * @param string|object $spec The class name (or object) for the translation.
     * 
     * @param string $key The translation key.
     * 
     * @param mixed $num Helps determine whether to get a singular
     * or plural translation.
     * 
     * @return string A translated locale string.
     * 
     */
    public static function locale($spec, $key, $num = 1)
    {
        // is the spec an object?
        if (is_object($spec)) {
            // yes, find its class
            $class = get_class($spec);
        } else {
            // no, assume the spec is a class name
            $class = (string) $spec;
        }
        
        // find all parents of this class, including this class
        $stack = Solar::parents($class, true);
        
        // add the vendor namespace to the stack for vendor-wide strings
        // and add Solar as the final fallback.
        $pos = strpos($class, '_');
        if ($pos !== false) {
            $vendor = substr($class, 0, $pos);
            $stack[] = $vendor;
            if ($vendor != 'Solar') {
                $stack[] = 'Solar';
            }
        } else {
            $stack[] = 'Solar';
        }
        
        // go through all classes and find the first matching
        // translation key
        foreach ($stack as $class) {
            
            // do we need to load locale strings for the class?
            if (! array_key_exists($class, Solar::$locale)) {
                // build the file name.  note that we use the fixdir()
                // method, which automatically replaces '/' with the
                // correct directory separator.
                $base = str_replace('_', '/', $class);
                $file = Solar::fixdir($base . '/Locale/')
                      . Solar::getLocale() . '.php';
        
                // can we find the file?
                if (Solar::fileExists($file)) {
                    // put the locale values into the shared locale array
                    Solar::$locale[$class] = (array) include $file;
                } else {
                    // could not find file.
                    // fail silently, as it's often the case that the
                    // translation file simply doesn't exist.
                    Solar::$locale[$class] = array();
                }
            }
        
            // does the key exist for the class?
            if (! empty(Solar::$locale[$class][$key])) {
                
                // get the translation of the key and force
                // to an array.
                $string = (array) Solar::$locale[$class][$key];
        
                // return the number-appropriate version of the
                // translated key, if multiple values exist.
                if ($num != 1 && ! empty($string[1])) {
                    return $string[1];
                } else {
                    return $string[0];
                }
            }
        }
        
        // never found a translation, return the requested key.
        return $key;
    }
    
    /**
     * 
     * Sets the locale code and clears out previous locale strings.
     * 
     * @param string $code A locale code, e.g., 'en_US'.
     * 
     * @return void
     */
    public static function setLocale($code)
    {
        // set the code
        Solar::$_locale_code = $code;
        
        // reset the strings
        Solar::$locale = array();
    }
    
    /**
     * 
     * Returns the current locale code.
     * 
     * @return string The current locale code, e.g., 'en_US'.
     * 
     */
    public static function getLocale()
    {
        return Solar::$_locale_code;
    }
    
    /**
     * 
     * Loads a class file from the include_path.
     * 
     * @param string $class A Solar (or other) class name.
     * 
     * @return void
     * 
     * @todo Add localization for errors
     * 
     */
    public static function loadClass($class)
    {
        // did we ask for a non-blank class?
        if (trim($class) == '') {
            throw Solar::exception(
                'Solar',
                'ERR_LOADCLASS_EMPTY',
                'No class named for loading',
                array('class' => $class)
            );
        }
        
        // pre-empt further searching for the class
        if (class_exists($class)) {
            return;
        }
        
        // convert the class name to a file path.
        $file = str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
        
        // include the file and check for failure. we use run() here
        // instead of require() so we can see the exception backtrace.
        $result = Solar::run($file);
        
        // if the class was not in the file, we have a problem.
        if (! class_exists($class)) {
            throw Solar::exception(
                'Solar',
                'ERR_LOADCLASS_EXIST',
                'Class does not exist in loaded file',
                array('class' => $class, 'file' => $file)
            );
        }
    }
    
    /**
     * 
     * Loads an interface file from the include_path.
     * 
     * @param string $interface A Solar (or other) interface class name.
     * 
     * @return void
     * 
     * @todo Add localization for errors
     * 
     */
    public static function loadInterface($interface)
    {
        // did we ask for a non-blank interface?
        if (trim($interface) == '') {
            throw Solar::exception(
                'Solar',
                'ERR_LOADINTERFACE_EMPTY',
                'No interface named for loading',
                array('interface' => $interface)
            );
        }

        // pre-empt further searching for the interface
        if (interface_exists($interface, false)) {
            return;
        }

        // convert the interface name to a file path.
        $file = str_replace('_', DIRECTORY_SEPARATOR, $interface) . '.php';

        // include the file and check for failure. we use run() here
        // instead of require() so we can see the exception backtrace.
        $result = Solar::run($file);

        // if the interface was not in the file, we have a problem.
        if (! interface_exists($interface)) {
            throw Solar::exception(
                'Solar',
                'ERR_LOADINTERFACE_EXIST',
                'Interface does not exist in loaded file',
                array('interface' => $interface, 'file' => $file)
            );
        }
    }
    
    /**
     * 
     * Uses [[php::include() | ]] to run a script in a limited scope.
     * 
     * @param string $file The file to include.
     * 
     * @return mixed The return value of the included file.
     * 
     */
    public static function run($file)
    {
        if (Solar::fileExists($file)) {
            // clean up the local scope, then include the file and
            // return its results
            unset($file);
            return include(func_get_arg(0));
        } else {
            // could not open the file for reading
            throw Solar::exception(
                'Solar',
                'ERR_FILE_NOT_READABLE',
                'File does not exist or is not readable',
                array('file' => $file)
            );
        }
    }
    
    /**
     * 
     * Hack for [[php::file_exists() | ]] that checks the include_path.
     * 
     * Use this to see if a file exists anywhere in the include_path.
     * 
     * {{code: php
     *     $file = 'path/to/file.php';
     *     if (Solar::fileExists('path/to/file.php')) {
     *         include $file;
     *     }
     * }}
     * 
     * @param string $file Check for this file in the include_path.
     * 
     * @return bool True if the file exists and is readble in the
     * include_path, false if not.
     * 
     */
    public static function fileExists($file)
    {
        // no file requested?
        $file = trim($file);
        if (! $file) {
            return false;
        }
        
        // using an absolute path for the file?
        // dual check for Unix '/' and Windows '\',
        // or Windows drive letter and a ':'.
        if ($file[0] == '/' || $file[0] == '\\' || $file[1] == ':') {
            return file_exists($file);
        }
        
        // using a relative path on the file
        $path = explode(PATH_SEPARATOR, ini_get('include_path'));
        foreach ($path as $dir) {
            // strip Unix '/' and Windows '\'
            $dir = rtrim($dir, '\\/');
            if (file_exists($dir . DIRECTORY_SEPARATOR . $file)) {
                return true;
            }
        }
        
        // never found it
        return false;
    }
    
    /**
     * 
     * Convenience method to instantiate and configure an object.
     * 
     * @param string $class The class name.
     * 
     * @param array $config Additional configuration array for the class.
     * 
     * @return object A new instance of the requested class.
     * 
     */
    public static function factory($class, $config = null)
    {
        Solar::loadClass($class);
        $obj = new $class($config);
        
        // is it an object factory?
        if (method_exists($obj, 'factory')) {
            // yes, return the class from the object factory
            return $obj->factory();
        }
        
        // return the object itself
        return $obj;
    }
    
    /**
     * 
     * Accesses an object in the registry.
     * 
     * @param string $key The registered name.
     * 
     * @return object The object registered under $key.
     * 
     * @todo Localize these errors.
     * 
     */
    public static function registry($key)
    {
        // has the shared object already been loaded?
        if (! Solar::isRegistered($key)) {
            throw Solar::exception(
                'Solar',
                'ERR_NOT_IN_REGISTRY',
                "Object with name '$key' not in registry.",
                array('name' => $key)
            );
        }
        
        // was the registration for a lazy-load?
        if (is_array(Solar::$registry[$key])) {
            $val = Solar::$registry[$key];
            $obj = Solar::factory($val[0], $val[1]);
            Solar::$registry[$key] = $obj;
        }
        
        // done
        return Solar::$registry[$key];
    }
    
    /**
     * 
     * Registers an object under a unique name.
     * 
     * @param string $key The name under which to register the object.
     * 
     * @param object|string $spec The registry specification.
     * 
     * @param mixed $config If lazy-loading, use this as the config.
     * 
     * @return void
     * 
     * @todo Localize these errors.
     * 
     */
    public static function register($key, $spec, $config = null)
    {
        if (Solar::isRegistered($key)) {
            // name already exists in registry
            $class = get_class(Solar::$registry[$key]);
            throw Solar::exception(
                'Solar',
                'ERR_REGISTRY_NAME_EXISTS',
                "Object with '$key' of class '$class' already in registry", 
                array('name' => $key, 'class' => $class)
            );
        }
        
        // register as an object, or as a class and config?
        if (is_object($spec)) {
            // directly register the object
            Solar::$registry[$key] = $spec;
        } elseif (is_string($spec)) {
            // register a class and config for lazy loading
            Solar::$registry[$key] = array($spec, $config);
        } else {
            throw Solar::exception(
                'Solar',
                'ERR_REGISTRY_FAILURE',
                'Please pass an object, or a class name and a config array',
                array()
            );
        }
    }
    
    /**
     * 
     * Check to see if an object name already exists in the registry.
     * 
     * @param string $key The name to check.
     * 
     * @return bool
     * 
     */
    public static function isRegistered($key)
    {
        return ! empty(Solar::$registry[$key]);
    }
    
    /**
     * 
     * Returns a dependency object.
     * 
     * @param string $class The dependency object should be an instance of this class.
     * Technically, this is more a hint than a requirement, although it will be used
     * as the class name if [[Solar::factory()]] gets called.
     * 
     * @param mixed $spec If an object, check to make sure it's an instance of $class.
     * If a string, treat as a [[Solar::registry()]] key. Otherwise, use this as a config
     * param to [[Solar::factory()]] to create a $class object.
     * 
     * @return object The dependency object.
     * 
     */
    public static function dependency($class, $spec)
    {
        // is it an object already?
        if (is_object($spec)) {
            return $spec;
        }
        
        // check for registry objects
        if (is_string($spec)) {
            return Solar::registry($spec);
        }
        
        // not an object, not in registry.
        // try to create an object with $spec as the config
        return Solar::factory($class, $spec);
    }
    
    /**
     * 
     * Safely gets a configuration group array or element value.
     * 
     * @param string $group The name of the group.
     * 
     * @param string $elem The name of the element in the group.
     * 
     * @param mixed $default If the group or element is not set, return
     * this value instead.  If this is not set and group was requested,
     * returns an empty array; if not set and an element was requested,
     * returns null.
     * 
     * @return mixed The value of the configuration group or element.
     * 
     */
    public static function config($group, $elem = null)
    {
        // was a default fallback value passed?  we do it this way
        // instead of defining a parameter because we need to return
        // a different default value depending on whether a group
        // was requested, or an element.
        if (func_num_args() > 2) {
            $default = func_get_arg(2);
        }
        
        // are we looking for a group or an element?
        if (is_null($elem)) {
            
            // looking for a group. if no default passed, set up an
            // empty array.
            if (! isset($default)) {
                $default = array();
            }
            
            // find the requested group.
            if (empty(Solar::$config[$group])) {
                return $default;
            } else {
                return Solar::$config[$group];
            }
            
        } else {
            
            // looking for an element. if no default passed, set up a
            // null.
            if (! isset($default)) {
                $default = null;
            }
            
            // find the requested group and element.
            if (! isset(Solar::$config[$group][$elem])) {
                return $default;
            } else {
                return Solar::$config[$group][$elem];
            }
        }
    }
    
    /**
     * 
     * Generates a simple exception, but does not throw it.
     * 
     * This method attempts to automatically load an exception class
     * based on the error code, falling back to parent exceptions
     * when no specific exception classes exist.  For example, if a
     * class named 'Vendor_Example' extended from 'Vendor_Base' throws an
     * exception or error coded as 'ERR_FILE_NOT_FOUND', the method will
     * attempt to return these exception classes in this order ...
     * 
     * 1. Vendor_Example_Exception_FileNotFound (class specific)
     * 
     * 2. Vendor_Base_Exception_FileNotFound (parent specific)
     * 
     * 3. Vendor_Example_Exception (class generic)
     * 
     * 4. Vendor_Base_Exception (parent generic)
     * 
     * 5. Vendor_Exception (generic for all of vendor)
     * 
     * The final fallback is always the generic Solar_Exception class.
     * 
     * Note that this method only generates the object; it does not
     * throw the exception.
     * 
     * {{code: php
     *     $class = 'My_Example_Class';
     *     $code = 'ERR_SOMETHING_WRONG';
     *     $text = 'Something is wrong.';
     *     $info = array('foo' => 'bar');
     *     $exception = Solar::exception($class, $code, $text, $info);
     *     throw $exception;
     * }}
     * 
     * In general, you shouldn't need to use this directly in classes
     * extended from [[Solar_Base::HomePage | Solar_Base]].  Instead, use
     * [[Solar_Base::_exception() | $this->_exception()]] for automated
     * picking of the right exception class from the $code, and
     * automated translation of the error message.
     * 
     * @param string|object $spec The class name (or object) that generated the exception.
     * 
     * @param mixed $code A scalar error code, generally a string.
     * 
     * @param string $text Any error message text.
     * 
     * @param array $info Additional error information in an associative
     * array.
     * 
     * @return Solar_Exception
     * 
     */
    public static function exception($spec, $code, $text = '',
        $info = array())
    {
        // is the spec an object?
        if (is_object($spec)) {
            // yes, find its class
            $class = get_class($spec);
        } else {
            // no, assume the spec is a class name
            $class = (string) $spec;
        }
        
        // drop 'ERR_' and 'EXCEPTION_' prefixes from the code
        // to get a suffix for the exception class
        $suffix = $code;
        if (substr($suffix, 0, 4) == 'ERR_') {
            $suffix = substr($suffix, 4);
        } elseif (substr($suffix, 0, 10) == 'EXCEPTION_') {
            $suffix = substr($suffix, 10);
        }
        
        // convert "STUDLY_CAP_SUFFIX" to "Studly Cap Suffix" ...
        $suffix = ucwords(strtolower(str_replace('_', ' ', $suffix)));
        
        // ... then convert to "StudlyCapSuffix"
        $suffix = str_replace(' ', '', $suffix);
        
        // build config array from params
        $config = array(
            'class' => $class,
            'code'  => $code,
            'text'  => $text,
            'info'  => (array) $info,
        );
        
        // get all parent classes, including the class itself
        $stack = Solar::parents($class, true);
        
        // add the vendor namespace, (e.g., 'Solar') to the stack as a
        // final fallback, even though it's not strictly part of the
        // hierarchy, for generic vendor-wide exceptions.
        $pos = strpos($class, '_');
        if ($pos !== false) {
            $stack[] = substr($class, 0, $pos);
        }
        
        // track through class stack and look for specific exceptions
        foreach ($stack as $class) {
            try {
                $obj = Solar::factory("{$class}_Exception_$suffix", $config);
                return $obj;
            } catch (Exception $e) {
                // do nothing
            }
        }
        
        // track through class stack and look for generic exceptions
        foreach ($stack as $class) {
            try {
                $obj = Solar::factory("{$class}_Exception", $config);
                return $obj;
            } catch (Exception $e) {
                // do nothing
            }
        }
        
        // last resort: a generic Solar exception
        return Solar::factory('Solar_Exception', $config);
    }
    
    /**
     * 
     * Dumps a variable to output.
     * 
     * Essentially, this is an alias to the Solar_Debug_Var::dump()
     * method, which buffers the [[php::var_dump | ]] for a variable,
     * applies some simple formatting for readability, [[php::echo | ]]s
     * it, and prints with an optional label.  Use this for
     * debugging variables to see exactly what they contain.
     * 
     * @param mixed &$var The variable to dump.
     * 
     * @param string $label A label for the dumped output.
     * 
     * @return void
     * 
     */
    public static function dump(&$var, $label = null)
    {
        $obj = Solar::factory('Solar_Debug_Var');
        echo $obj->dump($var, $label);
    }
    
    /**
     * 
     * "Fixes" a directory string for the operating system.
     * 
     * Use slashes anywhere you need a directory separator. Then run the
     * string through fixdir() and the slashes will be converted to the
     * proper separator (e.g. '\' on Windows).
     * 
     * Always adds a final trailing separator.
     * 
     * @param string $dir The directory string to 'fix'.
     * 
     * @return string The "fixed" directory string.
     * 
     */
    public static function fixdir($dir)
    {
        $dir = str_replace('/', DIRECTORY_SEPARATOR, $dir);
        return rtrim($dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }
    
    
    /**
     * 
     * Convenience method for dirname() and higher-level directories.
     * 
     * @param string $file Get the dirname() of this file.
     * 
     * @param int $up Move up in the directory structure this many 
     * times, default 0.
     * 
     * @return string The dirname() of the file.
     * 
     */
    public static function dirname($file, $up = 0)
    {
        $dir = dirname($file);
        while ($up --) {
            $dir = dirname($dir);
        }
        return $dir;
    }
    
    /**
     * 
     * Returns an array of the parent classes for a given class.
     * 
     * Parents in "reverse" order ... element 0 is the immediate parent,
     * element 1 the grandparent, etc.
     * 
     * @param string|object $spec The class or object to find parents
     * for.
     * 
     * @param bool $include_class If true, the class name is element 0,
     * the parent is element 1, the grandparent is element 2, etc.
     * 
     * @return array
     * 
     */
    public static function parents($spec, $include_class = false)
    {
        if (is_object($spec)) {
            $class = get_class($spec);
        } else {
            $class = $spec;
        }
        
        // do we need to load the parent stack?
        if (empty(Solar::$parents[$class])) {
            // get the stack of classes leading to this one
            Solar::$parents[$class] = array();
            $parent = $class;
            while ($parent = get_parent_class($parent)) {
                Solar::$parents[$class][] = $parent;
            }
        }
        
        // get the parent stack
        $stack = Solar::$parents[$class];
        
        // add the class itself?
        if ($include_class) {
            array_unshift($stack, $class);
        }
        
        // done
        return $stack;
    }
    
    /**
     *
     * Cleans the global scope of all variables that are found in other
     * super-globals.
     *
     * This code originally from Richard Heyes and Stefan Esser.
     * 
     * @return void
     * 
     */
    public function cleanGlobals()
    {
        $list = array(
            'GLOBALS',
            '_POST',
            '_GET',
            '_COOKIE',
            '_REQUEST',
            '_SERVER',
            '_ENV',
            '_FILES',
        );
        
        // Create a list of all of the keys from the super-global values.
        // Use array_keys() here to preserve key integrity.
        $keys = array_merge(
            array_keys($_ENV),
            array_keys($_GET),
            array_keys($_POST),
            array_keys($_COOKIE),
            array_keys($_SERVER),
            array_keys($_FILES),
            // $_SESSION = null if you have not started the session yet.
            // This insures that a check is performed regardless.
            isset($_SESSION) && is_array($_SESSION) ? array_keys($_SESSION) : array()
        );
        
        // Unset the globals.
        foreach ($keys as $key) {
            if (isset($GLOBALS[$key]) && ! in_array($key, $list)) {
                unset($GLOBALS[$key]);
            }
        }
    }
    
    /**
     * 
     * Fetches config file values.
     * 
     * @param mixed $spec A config specification.
     * 
     * @return array A config array.
     * 
     */
    public static function fetchConfig($spec = null)
    {
        // load the config file values.
        // use alternate config source if one is given.
        if (is_array($spec) || is_object($spec)) {
            $config = (array) $spec;
        } elseif (is_string($spec)) {
            // merge from array file return
            $config = (array) Solar::run($spec);
        } elseif ($spec === false) {
            $config = array();
        } else {
            // use the default config path
            $config = (array) Solar::run(SOLAR_CONFIG_PATH);
        }
        
        return $config;
    }
    
    /**
     * 
     * Returns the OS-specific directory for temporary files, optionally with
     * a path added to it.
     * 
     * @param string $add Add this to the end of the temporary directory
     * path.
     * 
     * @return string The temp directory path, with optional suffix added.
     * 
     */
    public static function temp($add = '')
    {
        if (function_exists('sys_get_temp_dir')) {
            $tmp = sys_get_temp_dir();
        } else {
            $tmp = Solar::_getTempDir();
        }
        
        if ($add) {
            $add = str_replace('/', DIRECTORY_SEPARATOR, $add);
            $add = ltrim($add, DIRECTORY_SEPARATOR);
            $tmp .= DIRECTORY_SEPARATOR . $add;
        }
        
        return $tmp;
    }
    
    /**
     * 
     * Returns the OS-specific temporary directory location.
     * 
     * @return string The temp directory path.
     * 
     */
    protected static function _getTempDir()
    {
        // non-Windows system?
        if (strtolower(substr(PHP_OS, 0, 3)) != 'win') {
            $tmp = empty($_ENV['TMPDIR']) ? getenv('TMPDIR') : $_ENV['TMPDIR'];
            if ($tmp) {
                return $tmp;
            } else {
                return '/tmp';
            }
        }
        
        // Windows 'TEMP'
        $tmp = empty($_ENV['TEMP']) ? getenv('TEMP') : $_ENV['TEMP'];
        if ($tmp) {
            return $tmp;
        }
    
        // Windows 'TMP'
        $tmp = empty($_ENV['TMP']) ? getenv('TMP') : $_ENV['TMP'];
        if ($tmp) {
            return $tmp;
        }
    
        // Windows 'windir'
        $tmp = empty($_ENV['windir']) ? getenv('windir') : $_ENV['windir'];
        if ($tmp) {
            return $tmp;
        }
    
        // final fallback for Windows
        return getenv('SystemRoot') . '\\temp';
    }
}
