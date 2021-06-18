<?php
namespace EquipTests\Handler;

use EquipTests\Configuration\ConfigurationTestTrait;
use Equip\Contract\ActionInterface;
use Equip\Configuration\AurynConfiguration;
use Equip\Configuration\DiactorosConfiguration;
use Equip\Handler\ActionHandler;
use EquipTests\Fake\FakeDomain;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\ServerRequest;

class ActionHandlerTest extends HandlerTestCase
{
    use ConfigurationTestTrait;

    protected function getConfigurations(): array
    {
        return [
            new AurynConfiguration(),
            new DiactorosConfiguration(),
        ];
    }

    public function testHandle()
    {
        $request = $this->injector->make(ServerRequest::class);
        $handler = $this->injector->make(ActionHandler::class);

        $action = $this->createMock(ActionInterface::class);
        $response = new Response();

        $action
            ->expects($this->once())
            ->method('__invoke')
            ->willReturn($response);

        $request = $request->withAttribute(ActionHandler::ACTION_ATTRIBUTE, $action);

        $this->t($request, $handler, function($request, $handler) use ($response) {
            $r = $handler->handle($request);
            $this->assertInstanceOf(Response::class, $r);
            $this->assertSame($r, $response);
            return $response;
        }, 'response');
    }
}
