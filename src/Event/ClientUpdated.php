<?php

namespace ChezRD\Jivochat\Webhooks\Event;

use ChezRD\Jivochat\Webhooks\Event;
use ChezRD\Jivochat\Webhooks\Model\EventRequest\ClientUpdatedRequest;

/**
 * Class ClientUpdated
 * 
 * The event is triggered by a client's data update.
 * For example, an agent was assigned to the client or client's category was changed.
 * This event is also sent when client's contact details are updated out of an active chat (in the CRM section - Clients).
 * 
 * @author Evgeny Rumiantsev <chezrd@gmail.com>
 * @package ChezRD\Jivochat\Webhooks\Event
 */
class ClientUpdated extends Event
{
    public ClientUpdatedRequest $request;
}