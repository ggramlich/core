<?php
/**
 * 
 * Helper for links.
 * 
 * @category Solar
 * 
 * @package Solar_View
 * 
 * @author Paul M. Jones <pmjones@solarphp.com>
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 * @version $Id: Link.php 1186 2006-05-21 15:38:37Z pmjones $
 * 
 */

/**
 * Solar_View_Helper
 */
Solar::loadClass('Solar_View_Helper');
 
/**
 * 
 * Helper for links.
 * 
 * @category Solar
 * 
 * @package Solar_View
 * 
 */
class Solar_View_Helper_Link extends Solar_View_Helper {
    
    /**
     * 
     * Returns a <link ... /> tag.
     * 
     * @param string $spec The specification array, typically
     * with keys 'rel' and 'href'.
     * 
     * @return string The <link ... /> tag.
     * 
     */
    public function link($spec)
    {
        return '<link' . $this->_view->attribs($spec) . ' />';
    }

}
?>