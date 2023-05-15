<?php

namespace ChezRD\Jivochat\Webhooks\Request;

use ChezRD\Jivochat\Webhooks\PopulateObjectViaArray;

class Call {
    use PopulateObjectViaArray;

    /** @var string Call type (callback, incoming, outgoing) */
    public $type;

    /** @var string Customer's phone number */
    public $phone;

    /** @var string Call status (start, end, agent_connected, client_connected, error) */
    public $status;

    /** @var string|null Link to .mp3 recording of a call (available if the call has ended) */
    public $record_url;

    /** @var string|null Error reason (avaiable if call status is an error) */
    public $reason;
}