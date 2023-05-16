<?php

namespace ChezRD\Jivochat\Webhooks\Event;

use ChezRD\Jivochat\Webhooks\Event;
use ChezRD\Jivochat\Webhooks\Model\EventRequest\ChatAssignedRequest;

/**
 * Class ChatAssigned
 *
 * Event will be sent when a chat connects to CRM using the parameter "crm_link" from reply on Chat Accepted.
 *
 * All known data about visitor and some agent's info will be sent in the request parameters.
 * Also parameters including visitor's id if it was sent to the widget using `jivo_api.setUserToken`.
 *
 * In response we expect only `{"result": "ok or an error message"}`.
 * 
 * @author Oleg Fedorov <olegf39@gmail.com>
 * @author Evgeny Rumiantsev <chezrd@gmail.com>
 * @package ChezRD\Jivochat\Webhooks\Event
 */
class ChatAssigned extends Event
{
    public ChatAssignedRequest $request;
}