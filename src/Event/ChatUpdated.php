<?php

namespace ChezRD\Jivochat\Webhooks\Event;

use ChezRD\Jivochat\Webhooks\Request\Agent;

/**
 * Class ChatUpdated
 * 
 * @author Oleg Fedorov <olegf39@gmail.com>
 * @author Evgeny Rumiantsev <chezrd@gmail.com>
 * @package ChezRD\Jivochat\Webhooks\Event
 */
class ChatUpdated extends Event
{
    /** @var Agent Object with information about the operator. See {@link Agent} for details. */
    public $agent;

    /**
     * Setter for {@link agent} property.
     *
     * @param Agent|array $data
     * @throws \InvalidArgumentException
     */
    public function setAgent($data) {
        return $this->populateFieldData('agent', Agent::class, $data, false, true);
    }
}