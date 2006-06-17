<?php
/**
 * 
 * Exception: a class method is not implemented.
 * 
 * @category Solar
 * 
 * @package Solar_Base
 * 
 * @author Paul M. Jones <pmjones@solarphp.com>
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 * @version $Id$
 * 
 */

/**
 * Solar_Base generic exception.
 */
Solar::loadClass('Solar_Base_Exception');

/**
 * 
 * Exception: a class method is not implemented.
 * 
 * @category Solar
 * 
 * @package Solar_Exception
 * 
 */
class Solar_Base_Exception_MethodNotImplemented extends Solar_Base_Exception {}
?>