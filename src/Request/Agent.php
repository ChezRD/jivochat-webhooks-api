<?php

namespace ChezRD\Jivochat\Webhooks\Request;

use ChezRD\Jivochat\Webhooks\PopulateObjectViaArray;

/**
 * Object with information about the operator (name, email etc).
 *
 * @author Oleg Fedorov <olegf39@gmail.com>
 * @package ChezRD\Jivochat\Webhooks\Request
 */
class Agent
{
    use PopulateObjectViaArray;

    /** @var int Operator ID (e.g. "3146"). */
    public $id;

    /** @var string Name of the operator (e.g. "Thomas Anderson"). */
    public $name;

    /** @var string Email of the operator (e.g. "agent@jivosite.com"). */
    public $email;
    
    /** @var string|null Phone of the operator (e.g. "+14083682346"). */
    public $phone;
}