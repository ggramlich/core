<?php
class Solar_Cli_Base extends Solar_Controller_Command {
    
    /**
     * 
     * Displays a "command not recognized" message.
     * 
     * @param string $cmd The requested command.
     * 
     * @return void
     * 
     */
    protected function _exec($cmd = null)
    {
        if ($cmd) {
            // we use 'null' command because we could have gotten here from
            // using an option or something else first, and a recognized 
            // command later on.  showing that command here would be confusing.
            $this->_println('ERR_UNKNOWN_COMMAND', 1, array('cmd' => null));
            $this->_println('HELP_TRY_SOLAR_HELP');
        } else {
            $this->_println($this->getInfoHelp());
        }
    }
    
    /**
     * 
     * Pre-exec logic.
     * 
     * Catches --version and -v to display version information and exit.
     * 
     * @return bool True to skip _exec(), false otherwise.
     * 
     */
    protected function _preExec()
    {
        $skip_exec = false;
        
        switch (true) {
        
        case $this->_options['version']:
            $this->_print("Solar command-line tool, version ");
            $this->_println($this->apiVersion() . '.');
            $skip_exec = true;
            break;
        
        }
        
        return $skip_exec;
    }
    
    /**
     * 
     * Post-execution logic.
     * 
     * @return void
     * 
     */
    protected function _postExec()
    {
        parent::_postExec();
        
        // return terminal to normal colors
        $this->_print("%n");
    }
}
