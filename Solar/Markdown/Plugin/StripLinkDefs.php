<?php
/**
 * 
 * _____
 * 
 * @category Solar
 * 
 * @package Solar_Markdown
 * 
 * @author John Gruber <http://daringfireball.net/projects/markdown/>
 * 
 * @author Michel Fortin <http://www.michelf.com/projects/php-markdown/>
 * 
 * @author Paul M. Jones <pmjones@solarphp.com>
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 * @version $Id$
 * 
 */

/**
 * Abstract plugin class.
 */
Solar::loadClass('Solar_Markdown_Plugin');

/**
 * 
 * _____
 * 
 * @category Solar
 * 
 * @package Solar_Markdown
 * 
 */
class Solar_Markdown_Plugin_StripLinkDefs extends Solar_Markdown_Plugin {
    
    /**
     * 
     * Removes link definitions from source and saves for later use.
     * 
     * @param string $text Markdown source text.
     * 
     * @return string The text without link definitions.
     * 
     */
    public function prepare($text)
    {
        $less_than_tab = $this->_getTabWidth() - 1;

        # Link defs are in the form: ^[id]: url "optional title"
        $text = preg_replace_callback('{
                ^[ ]{0,'.$less_than_tab.'}\[(.+)\]:  # id = $1
                  [ \t]*                             
                  \n?                                # maybe *one* newline
                  [ \t]*                             
                <?(\S+?)>?                           # url = $2
                  [ \t]*                             
                  \n?                                # maybe one newline
                  [ \t]*                             
                (?:                                  
                    (?<=\s)                          # lookbehind for whitespace
                    ["(]                             
                    (.+?)                            # title = $3
                    [")]
                    [ \t]*
                )?    # title is optional
                (?:\n+|\Z)
            }xm',
            array($this, '_prepare'),
            $text
        );
        
        return $text;
    }
    
    /**
     * 
     * Support callback for ____.
     * 
     * @param string $matches Matches from preg_replace_callback().
     * 
     * @return string The replacement text.
     * 
     */
    protected function _prepare($matches)
    {
        $name  = strtolower($matches[1]);
        $href  = $matches[2];
        $title = empty($matches[3]) ? null : $matches[3];
        
        // save the link
        $this->_config['markdown']->setLink($name, $href, $title);
        
        // done.
        // no return, it's supposed to be removed.
    }
}
?>