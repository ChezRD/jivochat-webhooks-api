<?php

namespace ChezRD\Jivochat\Webhooks\Request;

use ChezRD\Jivochat\Webhooks\PopulateObjectViaArray;

/**
 * Information about a page on which the visitor (URL and page title).
 *
 * @author Oleg Fedorov <olegf39@gmail.com>
 * @author Evgeny Rumiantsev <chezrd@gmail.com>
 * @package ChezRD\Jivochat\Webhooks\Request
 */
class Page
{
    use PopulateObjectViaArray;

    /** @var string URL of the page where the user is located (e.g. "http://example.com/"). */
    public $url;

    /** @var string|null Page Title (e.g. "Page title"). */
    public $title;
}