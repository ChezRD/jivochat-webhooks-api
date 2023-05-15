<?php

namespace ChezRD\Jivochat\Webhooks\Request\Session;

use ChezRD\Jivochat\Webhooks\PopulateObjectViaArray;

/**
 * Urchin Tracking Module (UTM) parameters from client (utm_source, utm_medium, utm_campaign, utm_term, utm_content).
 *
 * @author Evgeny Rumiantsev <chezrd@gmail.com>
 * @package ChezRD\Jivochat\Webhooks\Request\Session
 */
class UtmJson
{
    use PopulateObjectViaArray;

    /** @var string|null Identifies which site sent the traffic, and is a required parameter. (e.g. "(direct)"). */
    public $source;

    /** @var string|null Identifies what type of link was used, such as cost per click or email. (e.g. "cpc"). */
    public $medium;

    /** @var string|null Identifies a specific product promotion or strategic campaign. (e.g. "(direct)"). */
    public $campaign;

    /** @var string|null Identifies search terms. (e.g. "running+shoes"). */
    public $term;

    /** @var string|null Identifies what specifically was clicked to bring the user to the site, such as a banner ad or a text link. (e.g. "textlink"). */
    public $content;
}