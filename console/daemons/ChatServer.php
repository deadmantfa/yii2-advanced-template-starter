<?php

namespace console\daemons;

use consik\yii2websocket\events\WSClientEvent;
use consik\yii2websocket\WebSocketServer;
use Ratchet\ConnectionInterface;

class ChatServer extends WebSocketServer
{
    public function init(): void
    {
        parent::init();

        $this->on(self::EVENT_CLIENT_CONNECTED, function (WSClientEvent $e) {
            $e->client->name = null;
        });
    }


    protected function getCommand(ConnectionInterface $from, $msg): ?string
    {
        $request = json_decode($msg, true, 512, JSON_THROW_ON_ERROR);
        return !empty($request['action']) ? $request['action'] : parent::getCommand($from, $msg);
    }

    public function commandChat(ConnectionInterface $client, $msg): void
    {
        $request = json_decode($msg, true, 512, JSON_THROW_ON_ERROR);
        $result = ['message' => ''];

        if (!$client->name) {
            $result['message'] = 'Set your name';
        } elseif (!empty($request['message']) && $message = trim($request['message'])) {
            foreach ($this->clients as $chatClient) {
                $chatClient->send(json_encode([
                    'type' => 'chat',
                    'from' => $client->name,
                    'message' => $message
                ], JSON_THROW_ON_ERROR));
            }
        } else {
            $result['message'] = 'Enter message';
        }

        $client->send(json_encode($result, JSON_THROW_ON_ERROR));
    }

    public function commandSetName(ConnectionInterface $client, $msg): void
    {
        $request = json_decode($msg, true, 512, JSON_THROW_ON_ERROR);
        $result = ['message' => 'Username updated'];

        if (!empty($request['name']) && $name = trim($request['name'])) {
            $usernameFree = true;
            foreach ($this->clients as $chatClient) {
                if ($chatClient !== $client && $chatClient->name === $name) {
                    $result['message'] = 'This name is used by other user';
                    $usernameFree = false;
                    break;
                }
            }

            if ($usernameFree) {
                $client->name = $name;
            }
        } else {
            $result['message'] = 'Invalid username';
        }

        $client->send(json_encode($result));
    }

}
