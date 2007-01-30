<?php

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . '/Helper.php';

class Test_Solar_View_Helper_GetText extends Test_Solar_View_Helper {
    
    public function testGetText()
    {
        $actual = $this->_view->getText('ACTION_BROWSE');
        $expect = 'Browse';
        $this->assertSame($actual, $expect);
    }
    
    public function testGetText_otherClass()
    {
        $example = Solar::factory('Solar_Test_Example');
        $actual = $this->_view->getText('Solar_Test_Example::HELLO_WORLD');
        $expect = 'hello world';
        $this->assertSame($actual, $expect);
    }
    
    public function testGetText_resetClass()
    {
        $example = Solar::factory('Solar_Test_Example');
        $this->_view->getText('Solar_Test_Example::');
        $actual = $this->_view->getText('HELLO_WORLD');
        $expect = 'hello world';
        $this->assertSame($actual, $expect);
    }
    
    public function testGetText_badLocaleKey()
    {
        $actual = $this->_view->getText('no such "locale" key');
        $expect = 'no such &quot;locale&quot; key';
        $this->assertSame($actual, $expect);
    }
}
?>