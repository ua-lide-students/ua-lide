<?php

namespace Tests\WebSocketServer\MessageHandler;

use App\Exceptions\WrongMessageTypeException;
use App\WebSocketServer\MessageHandlers\InputMessageHandler;
use App\WebSocketServer\UserManager;
use PHPUnit\Framework\TestCase;
use Tests\WebSocketServer\Mocks\ConnectionInterfaceMock;

class InputMessageHandlerTest extends TestCase
{

    public function testHandle()
    {
        $userManagerStub = $this->createMock(UserManager::class);

        $userManagerStub->expects($this->once())
            ->method('writeInput')
            ->withConsecutive([
                $this->equalTo('abcde')
            ]);

        $data = [
            'input' => 'abcde'
        ];

        $handler = new InputMessageHandler();

        /** @noinspection PhpParamsInspection */
        $this->assertEquals(true, $handler->handle($userManagerStub, 'input', $data));
    }

    public function handleNonValidDataProvider()
    {
        return [
            'input_is_not_a_string' => [['input' => ['key' => 'value']]],
            'no_input_key' => [['not_input' => 'abcde']]
        ];
    }

    /**
     * @dataProvider handleNonValidDataProvider
     * @param array $data the message data
     */
    public function testHandleNonValidData(array $data)
    {
        $userManagerStub = $this->createMock(UserManager::class);


        $userManagerStub->expects($this->never())
            ->method('writeInput');

        $handler = new InputMessageHandler();

        /** @noinspection PhpParamsInspection */
        $this->assertEquals(false, $handler->handle($userManagerStub, 'input', $data));

    }

    /**
     */
    public function testHandleThrowExceptionOnWrongType()
    {
        $handler = new InputMessageHandler();

        $userManager = new UserManager(new ConnectionInterfaceMock(), "/home");

        $data = [];
        $this->expectException(WrongMessageTypeException::class);
        $handler->handle($userManager, "not_a_valid_type", $data);
    }

}
