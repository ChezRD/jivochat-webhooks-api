<?php

namespace ChezRD\Jivochat\Webhooks\Event;

/**
 * Class OfflineMessage
 * 
 * @author Oleg Fedorov <olegf39@gmail.com>
 * @package ChezRD\Jivochat\Webhooks\Event
 */
class OfflineMessage extends Event
{
    /** @var int Offline message ID (e.g. 1665399500726). */
    public $offline_message_id;

    /** @var string Message (e.g. "Message text"). */
    public $message;
}