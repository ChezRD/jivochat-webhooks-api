<?php

namespace ChezRD\Jivochat\Webhooks\Request;

use ChezRD\Jivochat\Webhooks\PopulateObjectViaArray;

/**
 * Object with the information about the department that visitor selected before chat
 *
 * @author Evgeny Rumiantsev <chezrd@gmail.com>
 * @package ChezRD\Jivochat\Webhooks\Request
 */
class Department
{
    use PopulateObjectViaArray;

    /** @var string Department ID. (e.g. "281")*/
    public $id;

    /** @var string Department name. (e.g. "Sales") */
    public $name;
}