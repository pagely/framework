<?php

namespace EquipTests\Middleware;

use Equip\Exception\MiddlewareException;
use Equip\Middleware\MiddlewareSet;
use PHPUnit\Framework\TestCase;
use Psr\Http\Server\MiddlewareInterface;
use stdClass;

class MiddlewareSetTest extends TestCase
{
    public function testWithInvalidEntries()
    {
        $this->expectException(MiddlewareException::class);
        $this->expectExceptionMessageMatches(
            '/Middleware .* is not a psr-15 middleware/i'
        );

        new MiddlewareSet([new stdClass]);
    }

    public function testWithValidEntries()
    {
        $middleware = [
            $this->createMock(MiddlewareInterface::class),
            $this->getMiddlewareClass(),
            function() {
            },
        ];
        $collection = new MiddlewareSet($middleware);
        $this->assertSame($middleware, $collection->toArray());
    }

    public function testAdd()
    {
        $collection = new MiddlewareSet;
        $this->assertEmpty($collection->toArray());

        $m1 = $this->getMiddlewareClass();
        $m2 = $this->getMiddlewareClass();
        $m3 = $this->getMiddlewareClass();

        $collection = $collection->withValue($m1);
        $this->assertContains($m1, $collection);

        // Insert the second middleware before the first and append the third.
        $collection = $collection->withValueBefore($m2, $m1);
        $collection = $collection->withValueAfter($m3, $m1);

        $this->assertSame([$m2, $m1, $m3], $collection->toArray());
    }

    /**
     * @return string
     */
    private function getMiddlewareClass()
    {
        $mock = $this->getMockBuilder(MiddlewareInterface::class)
                     ->setMockClassName("MiddlewareInterface_".uniqid())
                     ->getMock();
        return get_class($mock);
    }
}
