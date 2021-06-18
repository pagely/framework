<?php
namespace EquipTests\Handler;

use EquipTests\Configuration\ConfigurationTestTrait;
use Equip\Contract\ActionInterface;
use Equip\Configuration\AurynConfiguration;
use Equip\Configuration\DiactorosConfiguration;
use Equip\Configuration\DefaultMiddlewareConfiguration;
use Equip\Handler\ActionHandler;
use EquipTests\Fake\FakeDomain;
use Equip\Handler\ResponseEmitterHandler;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Stream;
use function ob_start;
use function ob_get_clean;

class ResponseEmitterHandlerTest extends HandlerTestCase
{
    use ConfigurationTestTrait;

    protected function getConfigurations(): array
    {
        return [
            new AurynConfiguration(),
            new DiactorosConfiguration(),
            new DefaultMiddlewareConfiguration(),
        ];
    }

    /**
     * @runInSeparateProcess
     */
    public function testHandle()
    {
        $request = $this->injector->make(ServerRequest::class);
        $actionHandler = $this->injector->make(ActionHandler::class);
        $emitter = $this->injector->make(ResponseEmitterHandler::class);

        $action = $this->createMock(ActionInterface::class);
        $stream = new Stream('php://memory', 'w+');
        $stream->write("Test\n");
        $stream->rewind();
        $response = new Response($stream);

        $action
            ->expects($this->once())
            ->method('__invoke')
            ->willReturn($response);

        $request = $request->withAttribute(ActionHandler::ACTION_ATTRIBUTE, $action);

        $ran = false;
        $this->t($request, [$emitter, $actionHandler], function($request, $handler) use (&$ran) {
            $ran = true;
            $this->expectOutputString("Test\n");
            $r = $handler->handle($request);

            return $r;
        }, 'response');

        $this->assertTrue($ran);
    }
}
