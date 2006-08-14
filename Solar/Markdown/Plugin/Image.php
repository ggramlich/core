<?php
Solar::loadClass('Solar_Markdown_Plugin');
class Solar_Markdown_Plugin_Image extends Solar_Markdown_Plugin {
    
    protected $_is_span = true;
    
    protected $_chars = '![]()';
    
    public function parse($text)
    {
        // First, handle reference-style labeled images: ![alt text][id]
        $text = preg_replace_callback('{
            (                                   # wrap whole match in $1
              !\[
                ('.$this->_nested_brackets.')   # alt text = $2
              \]

              [ ]?                              # one optional space
              (?:\n[ ]*)?                       # one optional newline followed by spaces
                            
              \[            
                (.*?)                           # id = $3
              \]

            )
            }xs', 
            array($this, '_parseReference'),
            $text
        );

        # Next, handle inline images:  ![alt text](url "optional title")
        # Don't forget: encode * and _
        $text = preg_replace_callback('{
            (                                   # wrap whole match in $1
              !\[
                ('.$this->_nested_brackets.')   # alt text = $2
              \]
              \(                                # literal paren
                [ \t]*      
                <?(\S+?)>?                      # src url = $3
                [ \t]*      
                (                               # $4
                  ([\'"])                       # quote char = $5
                  (.*?)                         # title = $6
                  \5                            # matching quote
                  [ \t]*    
                )?                              # title is optional
              \)
            )
            }xs',
            array($this, '_parseInline'),
            $text
        );

        return $text;
    }
    
    protected function _parseReference($matches)
    {
        $whole_match = $matches[1];
        $alt         = $matches[2];
        $name        = strtolower(trim($matches[3]));

        if (empty($name)) {
            // for shortcut links like ![this][].
            $name = strtolower($alt);
        }

        $link = $this->_config['markdown']->getLink($name);
        if ($link) {
            
            $href   = $this->_escape($link['href']);
            $alt    = $this->_escape($alt);
            $result = "<img src=\"$href\" alt=\"$alt\"";
            
            if (! empty($link['title'])) {
                $title = $this->_escape($link['title']);
                $result .=  " title=\"$title\"";
            }
            
            $result .= " />";
        
        } else {
            // no matching link reference
            $result = $whole_match;
        }

        // encode special Markdown characters
        $result = $this->_encode($result);
        
        return $result;
    }
    
    
    function _parseInline($matches)
    {
        $whole_match = $matches[1];
        $alt         = $matches[2];
        $href        = $matches[3];
        
        $alt    = $this->_escape($alt);
        $href   = $this->_escape($href);
        
        $result = "<img src=\"$href\" alt=\"$alt\"";
        
        if (! empty($matches[6])) {
            $title = $this->_escape($matches[6]);
            $result .=  " title=\"$title\"";
        }
        
        $result .= " />";
        
        // encode special Markdown characters
        $result = $this->_encode($result);
        
        return $result;
    }
}
?>