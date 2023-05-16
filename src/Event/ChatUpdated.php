<?php

namespace ChezRD\Jivochat\Webhooks\Event;

use ChezRD\Jivochat\Webhooks\Event;
use ChezRD\Jivochat\Webhooks\Model\EventRequest\ChatUpdatedRequest;

/**
 * Class ChatUpdated
 * 
 * The event is sent when a visitor's information has been updated - for example, a visitor has filled the contacts form in the chat. 
 * All known data about visitor is sent in the request parameters along with the information about the agent who accepted the chat. 
 * Also there may be visitor's ID if it was sent to the widget using setUserToken method.
 * 
 * @author Oleg Fedorov <olegf39@gmail.com>
 * @author Evgeny Rumiantsev <chezrd@gmail.com>
 * @package ChezRD\Jivochat\Webhooks\Event
 */
class ChatUpdated extends Event
{
    public ChatUpdatedRequest $request;
}