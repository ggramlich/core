<?php
/**
 * 
 * Helper for a set of radio button elements.
 * 
 * @category Solar
 * 
 * @package Solar_View
 * 
 * @author Paul M. Jones <pmjones@ciaweb.net>
 * 
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 * 
 * @version $Id: Solar_View_Helper_FormRadio.php 654 2006-01-11 17:10:06Z pmjones $
 * 
 */

/**
 * The abstract FormElement class.
 */
require_once 'Solar/View/Xhtml/FormElement.php';

/**
 * 
 * Helper for a set of radio button elements.
 * 
 * @category Solar
 * 
 * @package Solar_View
 * 
 * @author Paul M. Jones <pmjones@ciaweb.net>
 * 
 */
class Solar_View_Helper_FormRadio extends Solar_View_Helper_FormElement {
    
    /**
     * 
     * Generates a set of radio button elements.
     * 
     * @param array $info An array of element information.
     * 
     * @return string The element XHTML.
     * 
     */
    public function formRadio($info)
    {
        $this->_prepare($info);
        $radios = array();
        
        // default value if none are checked.
        $radios[] = $this->_view->formHidden(array('name' => $this->_name, 'value' => null)) . "\n";
        
        // add radio buttons.
        foreach ($this->_options as $opt_value => $opt_label) {
        
            // is it checked?
            if ($opt_value == $this->_value) {
                $this->_attribs['checked'] = 'checked';
            } else {
                unset($this->_attribs['checked']);
            }
            
            // build the radio button
            $radios[] = '<label><input type="radio"'
                      . ' name="' . $this->_view->escape($this->_name) . '"'
                      . ' value="' . $this->_view->escape($opt_value) . '"'
                      . $this->_view->attribs($this->_attribs) . ' />'
                      . $this->_view->escape($opt_label) . '</label>';
        }
        
        return implode("\n", $radios);
    }
}
?>