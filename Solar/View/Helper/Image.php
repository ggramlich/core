<?php

/**
 * 
 * Helper to generate an <img ... /> tag.
 * 
 * @category Solar
 * 
 * @package Solar_View
 * 
 * @author Paul M. Jones <pmjones@solarphp.com>
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 * @version $Id: Image.php 1707 2006-08-22 14:29:36Z pmjones $
 * 
 */

/**
 * Solar_View_Helper
 */
Solar::loadClass('Solar_View_Helper');
 
/**
 * 
 * Helper to generate an <img ... /> tag.
 * 
 * @category Solar
 * 
 * @package Solar_View
 * 
 */
class Solar_View_Helper_Image extends Solar_View_Helper {
	
	/**
	 * 
	 * Returns an <img ... /> tag.
	 * 
	 * If an "alt" attribute is not specified, will add it from the
	 * image [[php::basename() | ]].
	 * 
	 * @param string $src The href to the image source.
	 * 
	 * @param array $attribs Additional attributes for the tag.
	 * 
	 * @return string An <img ... /> tag.
	 * 
	 * @todo Add automated height/width calculation?
	 * 
	 */
	public function image($src, $attribs = array())
	{
	    unset($attribs['src']);
	    if (empty($attribs['alt'])) {
	        $attribs['alt'] = basename($src);
	    }
	    
	    return '<img src="' . $this->_view->publicHref($src) . '"'
	         . $this->_view->attribs($attribs) . ' />';
	}
}
?>