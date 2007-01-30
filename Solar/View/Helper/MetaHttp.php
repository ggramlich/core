<?php
/**
 * 
 * Helper for meta http-equiv tags.
 * 
 * @category Solar
 * 
 * @package Solar_View
 * 
 * @author Paul M. Jones <pmjones@solarphp.com>
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 * @version $Id: MetaHttp.php 1524 2006-07-21 17:21:02Z pmjones $
 * 
 */

/**
 * Solar_View_Helper
 */
Solar::loadClass('Solar_View_Helper');
 
/**
 * 
 * Helper for meta http-equiv tags.
 * 
 * @category Solar
 * 
 * @package Solar_View
 * 
 */
class Solar_View_Helper_MetaHttp extends Solar_View_Helper {
    
    /**
     * 
     * Returns a <meta http-equiv="" content="" /> tag.
     * 
     * @param string $key The http-equiv type.
     * 
     * @param string $val The content value.
     * 
     * @return string The <meta http-equiv="" content="" /> tag.
     * 
     */
    public function metaHttp($key, $val)
    {
        $spec = array(
            'http-equiv' => $key,
            'content' => $val,
        );
        return '<meta' . $this->_view->attribs($spec) . ' />';
    }

}
?>