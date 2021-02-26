<?php

namespace console\daemons;

use consik\yii2websocket\events\WSClientEvent;
use consik\yii2websocket\WebSocketServer;
use Ratchet\ConnectionInterface;
use yii\helpers\Json;

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
        $request = Json::decode($msg, true);
        return !empty($request['action']) ? $request['action'] : parent::getCommand($from, $msg);
    }

    public function commandContactList(ConnectionInterface $client, $msg): void
    {
        $data = [];
        foreach ($this->clients as $chatClient) {
            $data[] = [
                'name' => $chatClient->name,
                'fullName' => $chatClient->fullName,
                'avatarImg' => $chatClient->avatarImg,
            ];
        }
        foreach ($this->clients as $chatClient) {
            $chatClient->send(Json::encode([
                'type' => 'contactList',
                'from' => $client->name,
                'message' => $data,
            ], JSON_THROW_ON_ERROR));
        }

    }

    public function commandChat(ConnectionInterface $client, $msg): void
    {
        $request = Json::decode($msg, true);
        $result = ['message' => ''];

        if (!$client->name) {
            $result['message'] = 'Set your name';
        } elseif (!empty($request['message']) && $message = trim($request['message'])) {
            foreach ($this->clients as $chatClient) {
                $chatClient->send(Json::encode([
                    'type' => 'chat',
                    'from' => $client->name,
                    'message' => $message,
                    'fullName' => $client->fullName,
                    'avatarImg' => $client->avatarImg
                ], JSON_THROW_ON_ERROR));
            }
        } else {
            $result['message'] = 'Enter message';
        }

        $client->send(Json::encode($result, JSON_THROW_ON_ERROR));
    }

    public function commandSetName(ConnectionInterface $client, $msg): void
    {
        $request = Json::decode($msg, true);
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
                $client->fullName = $request['fullName'];
                $client->avatarImg = $request['avatarImg'];
            }
        } else {
            $result['message'] = 'Invalid username';
        }

        $client->send(Json::encode($result));
    }

}
