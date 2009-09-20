<?php
/**
 * 
 * Authenticate against an IMAP or POP3 mail server.
 * 
 * @category Solar
 * 
 * @package Solar_User
 * 
 * @author Paul M. Jones <pmjones@solarphp.com>
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 * @version $Id$
 * 
 */

/**
 * 
 * Authenticate against an IMAP or POP3 mail server.
 * 
 * @category Solar
 * 
 * @package Solar_User
 * 
 */
class Solar_User_Auth_Mail extends Solar_Base {
    
    /**
     * 
     * User-supplied configuration values.
     * 
     * Keys are:
     * 
     * : \\mailbox\\ : (string) An imap_open() mailbox string, e.g.
     * "mail.example.com:143/imap" or "mail.example.com:110/pop3".
     * 
     * @var array
     * 
     */
    protected $_config = array(
        'mailbox' => null,
    );
    
    /**
     * 
     * Constructor.
     * 
     * @param array $config User-supplied configuration.
     * 
     */
    public function __construct($config = null)
    {
        // make sure the IMAP extension is available
        if (! extension_loaded('imap')) {
            throw $this->_exception(
                'ERR_EXTENSION_NOT_LOADED',
                array('extension' => 'imap')
            );
        }
        
        // continue construction
        parent::__construct($config);
    }


    /**
     * 
     * Validates a username and password.
     * 
     * @param string $handle Username to authenticate.
     * 
     * @param string $passwd The password to use.
     * 
     * @return bool True on success, false on failure.
     * 
     * @todo Check the server status with fsockopen().
     * 
     */
    public function valid($handle, $passwd)
    {
        $mailbox = '{' . $this->_config['mailbox'] . '}';
        $conn = @imap_open($mailbox, $handle, $passwd, OP_HALFOPEN);
        if (is_resource($conn)) {
            @imap_close($conn);
            return true;
        } else {
            return false;
        }
    }
}
?>