<?php

namespace ChezRD\Jivochat\Webhooks\Event;

use ChezRD\Jivochat\Webhooks\Event;
use ChezRD\Jivochat\Webhooks\Model\EventRequest\ChatFinishedRequest;

/**
 * Class ChatFinished
 * 
 * This event is sent when the chat is closed in JivoChat app. 
 * All known data about visitor, agent information and the chat log are sent in the request parameters. 
 * Also there may be visitor's ID if it is sent to the widget using `jivo_api.setUserToken` method.
 * 
 * @author Oleg Fedorov <olegf39@gmail.com>
 * @author Evgeny Rumiantsev <chezrd@gmail.com>
 * @package ChezRD\Jivochat\Webhooks\Event
 */
class ChatFinished extends Event
{
    public ChatFinishedRequest $request;
}