<?php

namespace ChezRD\Jivochat\Webhooks\Event;

use ChezRD\Jivochat\Webhooks\Event;
use ChezRD\Jivochat\Webhooks\Model\EventRequest\OfflineMessageRequest;

/**
 * Class OfflineMessage
 * The event is sent when a visitor sends a message via the chat offline form.
 * All known data about visitor and the offline message are sent in the request parameters. 
 * Also the request may include visitor's ID if it has been sent to the widget using `jivo_api.setUserToken` method.
 * 
 * @author Oleg Fedorov <olegf39@gmail.com>
 * @author Evgeny Rumiantsev <chezrd@gmail.com>
 * @package ChezRD\Jivochat\Webhooks\Event
 */
class OfflineMessage extends Event
{
    public OfflineMessageRequest $request;
}