<?php

namespace ChezRD\Jivochat\Webhooks\Request;

use ChezRD\Jivochat\Webhooks\PopulateObjectViaArray;

/**
 * Information about the visitor (name, email, chats count etc).
 *
 * @author Oleg Fedorov <olegf39@gmail.com>
 * @author Evgeny Rumiantsev <chezrd@gmail.com>
 * @package ChezRD\Jivochat\Webhooks\Request
 */
class Visitor
{
    use PopulateObjectViaArray;

    /** @var int Visitor number (e.g. 2067). */
    public $number;

    /** @var int Number of visitor's chats (e.g. 5). */
    public $chats_count;
    
    /** @var string|null Visitor name (e.g. "John Smith"). */
    public $name;

    /** @var string|null Visitor email (e.g. "email@example.com"). */
    public $email;

    /** @var string|null Visitor phone (e.g. "+14084987855"). */
    public $phone;

    /** @var string|null Additional information about the client (e.g. "Description text"). */
    public $description;

    /** @var array|null Information about visitor's social accounts (e.g. null). */
    public $social;
    
}