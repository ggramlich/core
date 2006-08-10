<?php
Solar::loadClass('Solar_Markdown_Plugin');
class Solar_Markdown_Plugin_CodeBlock extends Solar_Markdown_Plugin {
    
    /**
     * 
     * Makes <pre><code>...</code></pre> blocks.
     * 
     * @param string $text Portion of the Markdown source text.
     * 
     * @return string The transformed XHTML.
     * 
     */
    public function parse($text)
    {
        $text = preg_replace_callback('{
                (?:\n\n|\A)
                (                                        # $1 = the code block -- one or more lines, starting with a space/tab
                  (?:
                    (?:[ ]{'.$this->_tab_width.'} | \t)  # Lines must start with a tab or a tab-width of spaces
                    .*\n+
                  )+
                )
                ((?=^[ ]{0,'.$this->_tab_width.'}\S)|\Z) # Lookahead for non-space at line-start, or end of doc
            }xm',
            array($this, '_parse'),
            $text
        );

        return $text;
    }
    
    /**
     * 
     * Support callback for code blocks.
     * 
     * @param array $matches Matches from preg_replace_callback().
     * 
     * @return string The replacement text.
     * 
     */
    protected function _parse($matches)
    {
        $code = $this->_outdent($matches[1]);
        
        // trim leading newlines and trailing whitespace
        $code = preg_replace(
            array('/\A\n+/', '/\s+\z/'),
            '',
            $code
        );

        $result = "<pre><code>"
                . $this->_escape($code)
                . "</code></pre>";

        return "\n\n"
             . $this->_tokenize($result)
             . "\n\n";
    }
}
?>