<?php

namespace ChezRD\Jivochat\Webhooks\Request;

use ChezRD\Jivochat\Webhooks\PopulateObjectViaArray;
use ChezRD\Jivochat\Webhooks\Request\Session\UtmJson;
use ChezRD\Jivochat\Webhooks\Request\Session\GeoIP;

/**
 * User session information (IP, "user agent" etc).
 *
 * @author Oleg Fedorov <olegf39@gmail.com>
 * @author Evgeny Rumiantsev <chezrd@gmail.com>
 * @package ChezRD\Jivochat\Webhooks\Request
 */
class Session
{
    use PopulateObjectViaArray;

    /** @var GeoIP Data from geoip. See {@link GeoIP} for details. */
    public $geoip;

    /** @var string|null utm (e.g. "campaign=(direct)|source=(direct)"). */
    public $utm;

    /** @var UtmJson|null utm (e.g. {"campaign": "(direct)","source": "(direct)"}). */
    public $utm_json;

    /** @var string IP address (e.g. "208.80.152.201"). */
    public $ip_addr;

    /** @var string User agent info. (e.g. "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.71 Safari/537.36"). */
    public $user_agent;

    /**
     * Setter for {@link geoip} property.
     *
     * @param GeoIP|array|null $data
     * @throws \InvalidArgumentException
     */
    public function setGeoip($data) {
        return $this->populateFieldData('geoip', GeoIP::class, $data, false, true);
    }

    /**
     * Setter for {@link utm_json} property.
     *
     * @param UtmJson|array|null $data
     * @throws \InvalidArgumentException
     */
    public function setUtmJson($data) {
        return $this->populateFieldData('utm_json', UtmJson::class, $data, false, true);
    }
}