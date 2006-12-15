<?php
require_once realpath(dirname(__FILE__) . '/../Adapter.php');

class Test_Solar_Cache_Adapter_Memcache extends Test_Solar_Cache_Adapter {

    protected $_Test_Solar_Cache_Adapter_Memcache = array(
        'adapter' => 'Solar_Cache_Adapter_Memcache',
        'config'  => array(
            'host' => 'localhost',
            'port' => 11211,
            'life' => 7, // 7 seconds
        ),
    );

    public function __construct($config = null)
    {
        if (! extension_loaded('memcache')) {
            $this->skip('memcache extension not loaded');
        }
        
        parent::__construct($config);
        
        // make sure we can connect
        try {
            $cache = Solar::factory('Solar_Cache', $this->_config);
        } catch (Exception $e) {
            echo $e;
            if ($e->getCode() == 'ERR_CONNECTION_FAILED' ) {
                $this->skip('memcache connection failed');
            } else {
                throw $e;
            }
        }
    }
    
    public function setup()
    {
        parent::setup();
        
        // for some reason, we need to wait a second before trying
        // to add/remove entries from the cache.  otherwise all the
        // tests fail.
        sleep(1);
    }
}
?>