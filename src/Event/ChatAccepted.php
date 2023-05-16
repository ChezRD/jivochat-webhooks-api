<?php

namespace ChezRD\Jivochat\Webhooks\Event;

use ChezRD\Jivochat\Webhooks\Event;
use ChezRD\Jivochat\Webhooks\Model\EventRequest\ChatAcceptedRequest;

/**
 * Class ChatAccepted
 *
 * Event will be sent when agent clicks 'Reply'.
 *
 * All known data about visitor and some agent's info will be sent in the request parameters.
 * Also parameters including visitor's id if it was sent to the widget using `jivo_api.setUserToken`.
 *
 * If response to `chat_accepted` contains contact_info,
 * this data will be displayed to the agent as if a visitor introduced in the chat window.
 * It's also will be saved in the archive and email with the chat log.
 *
 * @author Oleg Fedorov <olegf39@gmail.com>
 * @author Evgeny Rumiantsev <chezrd@gmail.com>
 * @package ChezRD\Jivochat\Webhooks\Event
 */
class ChatAccepted extends Event
{
    public ChatAcceptedRequest $request;
}