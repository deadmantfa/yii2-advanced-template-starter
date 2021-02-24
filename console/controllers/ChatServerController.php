<?php

namespace console\controllers;

use console\daemons\ChatServer;
use yii\console\Controller;

class ChatServerController extends Controller
{

    public function actionStart(): void
    {
        $server = new ChatServer();
        $server->port = 8080; //This port must be busy by WebServer and we handle an error

        $server->on(ChatServer::EVENT_WEBSOCKET_OPEN_ERROR, function () use ($server) {
            echo "Error opening port " . $server->port . "\n";
            ++$server->port; //Try next port to open
            $server->start();
        });

        $server->on(ChatServer::EVENT_WEBSOCKET_OPEN, function () use ($server) {
            echo "Server started at port " . $server->port;
        });

        $server->start();
    }
}
