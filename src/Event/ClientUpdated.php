<?php

namespace ChezRD\Jivochat\Webhooks\Event;

/**
 * Class ClientUpdated
 * 
 * @author Evgeny Rumiantsev <chezrd@gmail.com>
 * @package ChezRD\Jivochat\Webhooks\Event
 */
class ClientUpdated extends Event
{
    /** @var string Client ID (e.g. "1217"). */
    public $client_id;
    
    /** @var string Message (e.g. "Message text"). */
    public $message;
}