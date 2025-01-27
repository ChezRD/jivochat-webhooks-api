# Jivochat Webhooks API

[![Latest Stable Version](https://poser.pugx.org/chezrd/jivochat-webhooks-api/v/stable)](https://packagist.org/packages/chezrd/jivochat-webhooks-api)
[![License](https://poser.pugx.org/chezrd/jivochat-webhooks-api/license)](https://packagist.org/packages/chezrd/jivochat-webhooks-api)

Library for [Jivochat](https://www.jivochat.com) ([Jivosite](https://www.jivosite.ru)) Webhooks API integration.

This library allows you to integrate with Jivosite Webhooks API and:
* handle API calls in event-based manner;
* convert API requests JSON data in particular event objects;
* generate API responses;
* save original request (and generated response) data into MySQL or MongoDB server, and log it via Monolog.

For Russian documentation see [README-ru.md](README-ru.md).

## Requirements

The library requires PHP 7.0 or above for basic usage.

Optional requirements:
* [PDO extension](http://php.net/manual/en/book.pdo.php) allows logging of Webhooks request/response data to a MySQL server;
* [Monolog library](https://github.com/Seldaek/monolog) allows logging of Webhooks request/response using Monolog;
* [MongoDB library](https://github.com/mongodb/mongo-php-library) allows logging of Webhooks request/response data to a MongoDB server.

It is strongly recommended to have at least one of above loggers installed to hold a "backup" of original requests sent via Webhooks API.

## Installation

The preferred way to install this library is through [Composer](http://getcomposer.org/download/). To install the latest version, run:

```
composer require chezrd/jivochat-webhooks-api
```

## Basic usage

```php
<?php

use ChezRD\Jivochat\Webhooks\Log\MySQLLog;
use ChezRD\Jivochat\Webhooks\Event;
use ChezRD\Jivochat\Webhooks\EventListener;
use ChezRD\Jivochat\Webhooks\Model\EventRequest\ChatAcceptedRequest;
use ChezRD\Jivochat\Webhooks\Model\EventRequest\ChatFinishedRequest;
use ChezRD\Jivochat\Webhooks\Response;
use ChezRD\Jivochat\Webhooks\Response\SuccessResponse;
use ChezRD\Jivochat\Webhooks\Response\UpdateResponse;

// create MySQL logger
$dbLogger = new MySQLLog(new PDO('mysql:dbname=test;host=127.0.0.1', 'root', 'root'));

// create Callback API event listener
$listener = new EventListener([$dbLogger]);

// bind listener for `chat_accepted` event
$listener->on(Event::EVENT_CHAT_ACCEPTED, function (ChatAcceptedRequest $request): Response {
    // here you do your stuff - find user in your database, etc
    $user = User::getByEmail($request->visitor->email);
    
    // generate response on Callback API
    $response = new UpdateResponse();
    $response->setCRMLink(...);
    $response->setContactInfo(...);
    $response->setCustomData(...);
    
    // event handler must return Response object
    return $response;
});

// bind listener for `chat_accepted` event
$listener->on(Event::EVENT_CHAT_FINISHED, function (ChatFinishedRequest $request): Response {
    /** @var int Timestamp of the chat's first message. */
    $chatBeginAt = $request->chat->messages[0]->timestamp;
    // ...
    
    return new SuccessResponse();
});

// execute event listener
$listener->listen();
```

## Documentation

* Jivochat Webhooks API [official documentation](https://www.jivochat.com/api/#webhooks)

## License

This library is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Acknowledgements

Thanks to [original](https://github.com/Olegf13/jivochat-webhooks-api) Oleg Fedorov ([Olegf13](https://github.com/Olegf13)) library from 2017. 
Thanks to [this](https://github.com/nabarabane/jivosite) Jivosite Webhook handler library. 
