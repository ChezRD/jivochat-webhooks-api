<?php

namespace ChezRD\Jivochat\Webhooks\Request\Chat;

use ChezRD\Jivochat\Webhooks\PopulateObjectViaArray;

/**
 * Chat message.
 *
 * @author Oleg Fedorov <olegf39@gmail.com>
 * @author Evgeny Rumaintsev <chezrd@gmail.com>
 * @package ChezRD\Jivochat\Webhooks\Request\Chat
 */
class Message
{
    use PopulateObjectViaArray;

    /** @var int Timestamp of the message receipt (e.g. null). */
    public $timestamp;

    /** @var string Message Type ("visitor" - a message from a client, "agent" - a message from an agent). */
    public $type;

    /** @var int|null Agent ID, which responded to the message (exists only if {@link type} === "agent"). */
    public $agent_id;
    
    /** @var string Message body (e.g. "Hi, can I ..."). */
    public $message;
}