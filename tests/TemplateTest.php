<?php

class TemplateTest extends PHPUnit_Framework_TestCase
{
    public $template;

    public function setUp()
    {
        $this->template = require __DIR__ . '/../source/template.php';
    }

    public function testString()
    {
        $this->assertStringStartsWith('<?php', $this->template);
    }
}
