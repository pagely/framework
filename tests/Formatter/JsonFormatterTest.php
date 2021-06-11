<?php

namespace EquipTests\Formatter;

use Equip\Formatter\JsonFormatter;
use Equip\Payload;
use PHPUnit\Framework\TestCase;

class JsonFormatterTest extends TestCase
{
    /**
     * @var JsonFormatter
     */
    private $formatter;

    protected function setUp(): void
    {
        $this->formatter = new JsonFormatter();
    }

    public function testAccepts()
    {
        $this->assertEquals(['application/json'], JsonFormatter::accepts());
    }

    public function testType()
    {
        $this->assertEquals('application/json', $this->formatter->type());
    }

    public function testBody()
    {
        $payload = (new Payload)->withOutput([
            'success' => true,
        ]);

        $body = $this->formatter->body($payload);

        $this->assertEquals('{"success":true}', $body);
    }
}
