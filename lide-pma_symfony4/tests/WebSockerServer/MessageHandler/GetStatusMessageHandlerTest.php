<?php

namespace Tests\WebSocketServer\MessageHandler;

use App\Exceptions\WrongMessageTypeException;
use App\WebSocketServer\MessageHandlers\GetStatusMessageHandler;
use App\WebSocketServer\UserManager;
use PHPUnit\Framework\TestCase;
use Tests\WebSocketServer\Mocks\ConnectionInterfaceMock;

class GetStatusMessageHandlerTest extends TestCase
{
    public function testHandle()
    {
        $handler = new GetStatusMessageHandler();

        $userManagerStub = $this->createMock(UserManager::class);

        $userManagerStub->method('isContainerRunning')
            ->willReturn(true);

        $userManagerStub->expects($this->once())
            ->method('sendJson')
            ->withConsecutive([
                $this->equalTo([// Expected message to be sent
                    'type' => 'status',
                    'data' => [
                        'is_running' => true
                    ]
                ])
            ]);
        $data = [];
        /** @noinspection PhpParamsInspection */
        $handler->handle($userManagerStub, GetStatusMessageHandler::$Type, $data);
    }


    /**
     */
    public function testHandleThrowExceptionOnWrongType()
    {
        $handler = new GetStatusMessageHandler();

        $userManager = new UserManager(new ConnectionInterfaceMock(), "/home");

        $data = [];
        $this->expectException(WrongMessageTypeException::class);
        $handler->handle($userManager, "not_a_valid_type", $data);
    }
}
