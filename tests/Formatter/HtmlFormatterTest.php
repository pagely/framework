<?php

namespace EquipTests\Formatter;

use Equip\Formatter\HtmlFormatter;
use Equip\Payload;
use PHPUnit\Framework\TestCase;

class HtmlFormatterTest extends TestCase
{
    public function testAccepts()
    {
        $this->assertEquals(['text/html'], HtmlFormatter::accepts());
    }

    public function testType()
    {
        $formatter = $this->getMockForAbstractClass(HtmlFormatter::class);
        $this->assertEquals('text/html', $formatter->type());
    }
}
