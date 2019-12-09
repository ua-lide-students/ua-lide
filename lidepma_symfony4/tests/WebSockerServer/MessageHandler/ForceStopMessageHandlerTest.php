<?php

namespace Tests\WebSocketServer\MessageHandler;

use App\Exceptions\WrongMessageTypeException;
use App\WebSocketServer\MessageHandlers\ForceStopMessageHandler;
use App\WebSocketServer\UserManager;
use PHPUnit\Framework\TestCase;
use Tests\WebSocketServer\Mocks\ConnectionInterfaceMock;

class ForceStopMessageHandlerTest extends TestCase
{

    public function testHandle()
    {

        $userManagerStub = $this->createMock(UserManager::class);


        $userManagerStub->expects($this->once())
            ->method('stopContainer')
            ->willReturn(true);

        $handler = new ForceStopMessageHandler();

        $data = [];
        /** @noinspection PhpParamsInspection */
        $this->assertEquals(true, $handler->handle($userManagerStub, 'force_stop', $data));
    }

    public function testHandleThrowExceptionOnWrongType()
    {
        $handler = new ForceStopMessageHandler();

        $userManager = new UserManager(new ConnectionInterfaceMock(), "/home");

        $data = [];
        $this->expectException(WrongMessageTypeException::class);
        $handler->handle($userManager, "not_a_valid_type", $data);
    }

}
