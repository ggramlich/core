<?php
/**
 * 
 * Abstract class test.
 * 
 */
class Test_Solar_Log_Adapter extends Solar_Test {
    
    /**
     * 
     * Configuration values.
     * 
     * @var array
     * 
     */
    protected $_Test_Solar_Log_Adapter = array(
    );
    
    // -----------------------------------------------------------------
    // 
    // Support methods.
    // 
    // -----------------------------------------------------------------
    
    /**
     * 
     * Constructor.
     * 
     * @param array $config User-defined configuration parameters.
     * 
     */
    public function __construct($config = null)
    {
        $this->skip('abstract class');
        parent::__construct($config);
    }
    
    /**
     * 
     * Destructor; runs after all methods are complete.
     * 
     * @param array $config User-defined configuration parameters.
     * 
     */
    public function __destruct()
    {
        parent::__destruct();
    }
    
    /**
     * 
     * Setup; runs before each test method.
     * 
     */
    public function setup()
    {
        parent::setup();
    }
    
    /**
     * 
     * Setup; runs after each test method.
     * 
     */
    public function teardown()
    {
        parent::teardown();
    }
    
    // -----------------------------------------------------------------
    // 
    // Test methods.
    // 
    // -----------------------------------------------------------------
    
    /**
     * 
     * Test -- Constructor.
     * 
     */
    public function test__construct()
    {
        $obj = Solar::factory('Solar_Log_Adapter');
        $this->assertInstance($obj, 'Solar_Log_Adapter');
    }
    
    /**
     * 
     * Test -- Gets the list of events this adapter recognizes.
     * 
     */
    public function testGetEvents()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Saves (writes) an event and message to the log.
     * 
     */
    public function testSave()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Sets the list of events this adapter recognizes.
     * 
     */
    public function testSetEvents()
    {
        $this->todo('stub');
    }
}
