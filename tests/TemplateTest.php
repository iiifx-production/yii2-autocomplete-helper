<?php

use PHPUnit\Framework\TestCase;

class TemplateTest extends TestCase
{
    public $template;

    public function setUp(): void
    {
        $this->template = require __DIR__ . '/../source/template.php';
    }

    public function testString()
    {
        $this->assertStringStartsWith('<?php', $this->template);
    }
}
