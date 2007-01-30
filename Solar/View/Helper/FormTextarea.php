<?php
/**
 * 
 * Helper for a 'textarea' element.
 * 
 * @category Solar
 * 
 * @package Solar_View
 * 
 * @author Paul M. Jones <pmjones@solarphp.com>
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 * @version $Id: FormTextarea.php 1186 2006-05-21 15:38:37Z pmjones $
 * 
 */

/**
 * The abstract FormElement class.
 */
Solar::loadClass('Solar_View_Helper_FormElement');

/**
 * 
 * Helper for a 'textarea' element.
 * 
 * @category Solar
 * 
 * @package Solar_View
 * 
 * @author Paul M. Jones <pmjones@solarphp.com>
 * 
 */
class Solar_View_Helper_FormTextarea extends Solar_View_Helper_FormElement {
    
    /**
     * 
     * Generates a 'textarea' element.
     * 
     * @param array $info An array of element information.
     * 
     * @return string The element XHTML.
     * 
     */
    public function formTextarea($info)
    {
        $this->_prepare($info);
        return '<textarea'
             . ' name="' . $this->_view->escape($this->_name) . '"'
             . $this->_view->attribs($this->_attribs) . '>'
             . $this->_view->escape($this->_value)
             . '</textarea>';
    }
}
?>