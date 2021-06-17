<?php

namespace EquipTests\Handler;

use Equip\Contract\ActionInterface;
use Equip\Directory;
use Equip\Exception\HttpException;
use Equip\Handler\ActionHandler;
use Equip\Handler\DispatchHandler;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Uri;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use \Closure;

class DispatchHandlerTest extends HandlerTestCase
{
    /**
     * @var Directory
     */
    private $directory;

    protected function setUp(): void
    {
        $this->directory = new Directory;
    }

    public function testHandle()
    {
        $action = $this->createMock(ActionInterface::class);
        $directory = $this->directory->get('/[{name}]', $action);
        $request = $this->getRequest('GET', '/tester');
        $response = $this->dispatch($directory, $request, function($request, $handler) use ($action) {
            $this->assertSame($action, $request->getAttribute(ActionHandler::ACTION_ATTRIBUTE));
            $this->assertSame('tester', $request->getAttribute('name'));
            return $handler->handle($request);
        });
    }

    public function testPrefixed()
    {
        $action = $this->createMock(ActionInterface::class);
        $directory = $this->directory->withPrefix('prefix');
        $directory = $directory->get('/[{name}]', $action);
        $request = $this->getRequest('GET', '/prefix/tester');

        $response = $this->dispatch($directory, $request, function($request, $handler) use ($action) {
            $this->assertSame($action, $request->getAttribute(ActionHandler::ACTION_ATTRIBUTE));
            $this->assertSame('tester', $request->getAttribute('name'));
            return $handler->handle($request);
        });
    }

    public function testNotFoundException()
    {
        $this->expectException(HttpException::class);
        $this->expectExceptionMessageMatches('/cannot find any resource at/i');

        $request = $this->getRequest('GET', '/');

        $response = $this->dispatch($this->directory, $request, function($request, $handler) {
            return $request->handle($request);
        });
    }

    public function testMethodNotAllowedException()
    {
        $this->expectException(HttpException::class);
        $this->expectExceptionMessageMatches('/cannot access resource .* using method/i');

        $action = $this->createMock(ActionInterface::class);
        $handler = new DispatchHandler($this->directory);
        $request = $this->getRequest('POST');

        $directory = $this->directory->get('/', $action);

        $response = $this->dispatch($directory, $request, function($request, $handler) {
            return $request->handle($request);
        });
    }

    protected function dispatch(Directory $directory, ServerRequestInterface $request, Closure $asserter): ResponseInterface
    {
        $dispatcher = new DispatchHandler($directory);
        return $this->t($request, $dispatcher, $asserter);
    }

}
