<?php
namespace EquipTests\Handler;

use Equip\Exception\HttpBadRequestException;
use Equip\Handler\JsonContentHandler;
use Laminas\Diactoros\Request;
use Laminas\Diactoros\ServerRequest;
use Middlewares\Utils\HttpErrorException;

class JsonContentHandlerTest extends ContentHandlerTestCase
{
    public function testInvokeWithApplicableMimeType()
    {
        $request = $this->getRequest(
            $mime = 'application/json',
            json_encode($body = ['test' => 'json'])
        );
        $ran = false;
        $this->t(
            $request,
            new JsonContentHandler(),
            function ($req, $handler) use ($mime, $body, &$ran) {
                $ran = true;
                $this->assertSame($mime, $req->getHeaderLine('Content-Type'));
                $this->assertEquals($body, $req->getParsedBody());
                return $handler->handle($req);
            }
        );
        $this->assertTrue($ran);
    }

    public function testInvokeWithMalformedBody()
    {
        $this->expectException(HttpErrorException::class);
        $this->expectExceptionCode(400);
        $request = $this->getRequest(
            $mime = 'application/json',
            $body = '{not json}'
        );
        $this->t(
            $request,
            new JsonContentHandler(),
            function ($req, $res) {
                $this->fail('Handler callback unexpectedly invoked');
            }
        );
    }

    public function testInvokeWithNonApplicableMimeType()
    {
        $request = $this->getRequest(
            $mime = 'application/x-www-form-urlencoded',
            $body = http_build_query(['test' => 'form'], '', '&')
        );

        $ran = false;
        $this->t(
            $request,
            new JsonContentHandler(),
            function ($req, $handler) use ($mime, &$ran) {
                $ran = true;
                $this->assertSame($mime, $req->getHeaderLine('Content-Type'));
                $this->assertNull($req->getParsedBody());
                return $handler->handle($req);
            }
        );
        $this->assertTrue($ran);
    }
}
