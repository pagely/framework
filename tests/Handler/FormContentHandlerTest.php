<?php
namespace EquipTests\Handler;

use Equip\Handler\FormContentHandler;
use Laminas\Diactoros\Response;

class FormContentHandlerTest extends ContentHandlerTestCase
{
    public function testInvokeWithApplicableMimeType()
    {
        $request = $this->getRequest(
            $mime = 'application/x-www-form-urlencoded',
            http_build_query($body = ['test' => 'form'], '', '&')
        );

        $ran = false;
        $this->t(
            $request,
            new FormContentHandler(),
            function ($req, $handler) use ($mime, $body, &$ran) {
                $ran = true;
                $this->assertSame($mime, $req->getHeaderLine('Content-Type'));
                $this->assertSame($body, $req->getParsedBody());
                return $handler->handle($req);
            }
        );
        $this->assertTrue($ran);
    }

    public function testInvokeWithNonApplicableMimeType()
    {
        $request = $this->getRequest(
            $mime = 'application/json',
            $body = json_encode((object) ['test' => 'json'])
        );

        $ran = false;
        $this->t(
            $request,
            new FormContentHandler(),
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
