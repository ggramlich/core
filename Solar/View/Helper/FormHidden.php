<?php
/**
 * 
 * Helper for a 'hidden' element.
 * 
 * @category Solar
 * 
 * @package Solar_View
 * 
 * @author Paul M. Jones <pmjones@solarphp.com>
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 * @version $Id: FormHidden.php 1186 2006-05-21 15:38:37Z pmjones $
 * 
 */

/**
 * The abstract FormElement class.
 */
Solar::loadClass('Solar_View_Helper_FormElement');

/**
 * 
 * Helper for a 'hidden' element.
 * 
 * @category Solar
 * 
 * @package Solar_View
 * 
 * @author Paul M. Jones <pmjones@solarphp.com>
 * 
 */
class Solar_View_Helper_FormHidden extends Solar_View_Helper_FormElement {
    
    /**
     * 
     * Generates a 'hidden' element.
     * 
     * @param array $info An array of element information.
     * 
     * @return string The element XHTML.
     * 
     */
    public function formHidden($info)
    {
        $this->_prepare($info);
        return '<input type="hidden"'
             . ' name="' . $this->_view->escape($this->_name) . '"'
             . ' value="' . $this->_view->escape($this->_value) . '"'
             . $this->_view->attribs($this->_attribs)
             . ' />';
    }
}
?>