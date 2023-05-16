<?php

namespace ChezRD\Jivochat\Webhooks\Event;

use ChezRD\Jivochat\Webhooks\Event;
use ChezRD\Jivochat\Webhooks\Model\EventRequest\CallEventRequest;

/**
 * CallEvent event occurs when agents get a new call or an existing call status changes.
 * 
 * @author Evgeny Rumiantsev <chezrd@gmail.com>
 * @package ChezRD\Jivochat\Webhooks\Event
 */
class CallEvent extends Event
{
    public CallEventRequest $request;
}