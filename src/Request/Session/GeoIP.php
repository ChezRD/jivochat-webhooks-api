<?php

namespace ChezRD\Jivochat\Webhooks\Request\Session;

use ChezRD\Jivochat\Webhooks\PopulateObjectViaArray;

/**
 * Geo data about the user (country, city, coordinates etc).
 *
 * @author Oleg Fedorov <olegf39@gmail.com>
 * @author Evgeny Rumiantsev <chezrd@gmail.com>
 * @package ChezRD\Jivochat\Webhooks\Request\Session
 */
class GeoIP
{
    use PopulateObjectViaArray;

    /** @var string Area code (e.g. "CA"). */
    public $region_code;

    /** @var string Region (e.g. "California"). */
    public $region;

    /** @var string ISO country code (e.g. "US"). */
    public $country_code;

    /** @var string Country name (e.g. "United States"). */
    public $country;

    /** @var string City (e.g. "San Francisco"). */
    public $city;

    /** @var string Empty string or ISP if provided, (e.g. "" or "Skartel"). */
    public $isp;

    /** @var string Latitude (e.g. "37.7898"). */
    public $latitude;

    /** @var string Longitude (e.g. "-122.3942"). */
    public $longitude;
    
    /** @var string Empty string or ISP if provided, (e.g. "" or "Skartel"). */
    public $organization;
}