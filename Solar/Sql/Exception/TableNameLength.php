<?php
/**
 * 
 * Exception: table name is too long or too short.
 * 
 * @category Solar
 * 
 * @package Solar_Sql
 * 
 * @author Paul M. Jones <pmjones@solarphp.com>
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 * @version $Id: TableNameLength.php 1552 2006-07-27 22:01:46Z pmjones $
 * 
 */

/**
 * Base SQL exception.
 */
Solar::loadClass('Solar_Sql_Exception');

/**
 * 
 * Exception: table name is too long or too short.
 * 
 * @category Solar
 * 
 * @package Solar_Sql
 * 
 */
class Solar_Sql_Exception_TableNameLength extends Solar_Sql_Exception {}
?>