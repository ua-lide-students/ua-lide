<?php
/**
 * Created by PhpStorm.
 * User: etudiant
 * Date: 01/10/18
 * Time: 16:32
 */

namespace App\WebSocketServer\MessageHandlers;


use App\Exceptions\WrongMessageTypeException;
use App\WebSocketServer\MessageHandler;
use App\WebSocketServer\UserManager;

class ForceStopMessageHandler implements MessageHandler
{

    public static $Type = 'force_stop';

    public function handle(UserManager $sender, string $type, array &$data): bool
    {
        if (self::$Type !== $type) {
            throw new WrongMessageTypeException(self::$Type, $type);
        }

        return $sender->stopContainer();
    }
}