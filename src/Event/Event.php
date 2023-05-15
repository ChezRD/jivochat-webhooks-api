<?php

namespace ChezRD\Jivochat\Webhooks\Event;

use ChezRD\Jivochat\Webhooks\PopulateObjectViaArray;
use ChezRD\Jivochat\Webhooks\Request\Agent;
use ChezRD\Jivochat\Webhooks\Request\Page;
use ChezRD\Jivochat\Webhooks\Request\Session;
use ChezRD\Jivochat\Webhooks\Request\Visitor;
use ChezRD\Jivochat\Webhooks\Request\Analytics;
use ChezRD\Jivochat\Webhooks\Request\Department;
use ChezRD\Jivochat\Webhooks\Request\Organization;
use ChezRD\Jivochat\Webhooks\Request\Status;
use ChezRD\Jivochat\Webhooks\Request\Tag;
use InvalidArgumentException;

/**
 * Class Event
 *
 * @author Oleg Fedorov <olegf39@gmail.com>
 * @author Evgeny Rumiantsev <chezrd@gmail.com>
 * @package ChezRD\Jivochat\Webhooks\Event
 */
abstract class Event
{
    use PopulateObjectViaArray;

    /**
     * Event will be sent when agent clicks 'Reply'.
     *
     * All known data about visitor and some agent's info will be sent in the request parameters.
     * Also parameters including visitor's id if it was sent to the widget using `jivo_api.setUserToken`.
     *
     * If response to `chat_accepted` contains contact_info,
     * this data will be displayed to the agent as if a visitor introduced in the chat window.
     * It's also will be saved in the archive and email with the chat log.
     */
    const EVENT_CHAT_ACCEPTED = 'chat_accepted';

    /**
     * Event will be sent when a chat connects to CRM using the parameter "crm_link" from reply on Chat Accepted.
     *
     * All known data about visitor and some agent's info will be sent in the request parameters.
     * Also parameters including visitor's id if it was sent to the widget using `jivo_api.setUserToken`.
     *
     * In response we expect only `{"result": "ok or an error message"}`.
     */
    const EVENT_CHAT_ASSIGNED = 'chat_assigned';

    /**
     * Event will be sent when a chat is closed in the agent application.
     *
     * All known data about visitor, agent's info and the chat log will be sent in the request parameters.
     * Also parameters including visitor's id if it was sent to the widget using `jivo_api.setUserToken`.
     *
     * In response we expect only `{"result": "ok or an error message"}`.
     */
    const EVENT_CHAT_FINISHED = 'chat_finished';

    /**
     * Event will be sent when a visitor's information has been updated
     * (for example a visitor filled the contacts form in the chat).
     *
     * All known data about visitor and agent's info will be sent in the request parameters.
     * Also parameters including visitor's id if it was sent to the widget using `jivo_api.setUserToken`.
     *
     * In response we expect only `{"result": "ok or an error message"}`.
     */
    const EVENT_CHAT_UPDATED = 'chat_updated';

    /**
     * Event will be sent when a visitor sends an offline message through the chat offline form.
     *
     * All known data about visitor and offline message will be sent in the request parameters.
     * Also parameters including visitor's id if it was sent to the widget using `jivo_api.setUserToken`.
     *
     * In response we expect only `{"result": "ok or an error message"}`.
     */
    const EVENT_OFFLINE_MESSAGE = 'offline_message';

     /**
     * The event is triggered by a client's data update. 
     * 
     * For example, an agent was assigned to the client or client's category was changed. 
     * This event is also sent when client's contact details are updated out of an active chat (in the CRM section - Clients).
     *
     * In response we expect only `{"result": "ok or an error message"}`.
     */
    const EVENT_CLIENT_UPDATED = 'client_updated';

    /**
     * This event occurs when agents get a new call or an existing call status changes.
     */
    const EVENT_CALL_EVENT = 'call_event';

    /** Available events list. */
    const EVENTS = [
        self::EVENT_CALL_EVENT,
        self::EVENT_CHAT_ACCEPTED,
        self::EVENT_CHAT_ASSIGNED,
        self::EVENT_CHAT_FINISHED,
        self::EVENT_CHAT_UPDATED,
        self::EVENT_OFFLINE_MESSAGE,
        self::EVENT_CLIENT_UPDATED,
    ];

    /** @var string Type of event (e.g. "chat_accepted"). See {@link EventListener::EVENTS} for available values. */
    public $event_name;

    /** @var string Channel widget ID, it can be found in the chat code (e.g. "3948"). */
    public $widget_id;

    /** @var int ID of current chat (e.g. 7507). */
    public $chat_id;

    /** @var Visitor Object with information about the visitor. See {@link Visitor} for details. */
    public $visitor;

    /** @var Session Information on user sessions. See {@link Session} for details. */
    public $session;

    /** @var string|null Visitor id (e.g. "3c077929b8_12175"). */
    public $user_token;

    /** @var Page|null Information about a page on which the visitor. See {@link Page} for details. */
    public $page;

    /** @var Analytics|null Available client identificators in Google Analytics or Yandex.Metrika. See {@link Analytics} for details. */
    public $analytics;

    /** @var Department|null Object with the information about the department that visitor selected before chat. See {@link Department} for details. */
    public $department;

    /** @var Organization|null Object with the information about an organization, client was assigned. See {@link Organization} for details. */
    public $organization;

    /** @var Agent|null Information about an agent assigned to the client */
    public $assigned_agent;

    /** @var Tag[]|null Tags selected for the client */
    public $tags;

    /** @var Status|null Tags selected for the client */
    public $status;

    /**
     * Event constructor.
     *
     * @param array $requestData
     */
    final protected function __construct(array $requestData)
    {
        $this->populate($requestData);
    }

    /**
     * Creates object of concrete Event class and populates its properties via given Webhook request data.
     *
     * @param string $requestJSON Webhook request JSON string.
     * @return Event Returns created object of concrete event (see {@link Event::EVENTS}).
     * @throws InvalidArgumentException when couldn't decode request string or couldn't detect concrete event type.
     * @throws \LogicException in case when got unknown event name or class for given event name is not implemented.
     */
    final public static function create(string $requestJSON): Event
    {
        /** @var array Decoded Webhook request data array (assoc). */
        $decodedRequest = json_decode($requestJSON, true);
        if (null === $decodedRequest) {
            $errorCode = json_last_error();
            $error = json_last_error_msg();

            throw new InvalidArgumentException("Couldn't decode request string, error ({$errorCode}): `{$error}`.");
        }

        /** @var string Event name. */
        $eventName = $decodedRequest['event_name'] ?: null;
        if (null === $eventName) {
            throw new InvalidArgumentException("Request doesn't contain `event_name` field (not a Jivochat Webhook?).");
        }

        if (!in_array($eventName, static::EVENTS, true)) {
            throw new \LogicException("Got unknown event name from Webhook request (`{$eventName}`).");
        }

        switch ($eventName) {
            case static::EVENT_CALL_EVENT:
                return new CallEvent($decodedRequest);
                break;
            case static::EVENT_CLIENT_UPDATED:
                return new ClientUpdated($decodedRequest);
                break;
            case static::EVENT_CHAT_ACCEPTED:
                return new ChatAccepted($decodedRequest);
                break;
            case static::EVENT_CHAT_ASSIGNED:
                return new ChatAssigned($decodedRequest);
                break;
            case static::EVENT_CHAT_UPDATED:
                return new ChatUpdated($decodedRequest);
                break;
            case static::EVENT_CHAT_FINISHED:
                return new ChatFinished($decodedRequest);
                break;
            case static::EVENT_OFFLINE_MESSAGE:
                return new OfflineMessage($decodedRequest);
                break;
            default:
                throw new \LogicException("Class for event name `{$eventName}` is not implemented.");
                break;
        }
    }

    /**
     * Setter for {@link visitor} property.
     *
     * @param Visitor|array $data
     * @throws InvalidArgumentException
     */
    public function setVisitor($data) {
        return $this->populateFieldData('visitor', Visitor::class, $data, false, false);
    }

    /**
     * Setter for {@link session} property.
     *
     * @param Session|array $data
     * @throws InvalidArgumentException
     */
    public function setSession($data) {
        return $this->populateFieldData('session', Session::class, $data, false, false);
    }

    /**
     * Setter for {@link page} property.
     *
     * @param Visitor|array|null $data
     * @throws InvalidArgumentException
     */
    public function setPage($data) {
        return $this->populateFieldData('page', Page::class, $data, false, true);
    }

    /**
     * Setter for {@link assigned_agent} property.
     *
     * @param Agent|array|null $data
     * @throws InvalidArgumentException
     */
    public function setAssignedAgent($data) {
        return $this->populateFieldData('assigned_agent', Agent::class, $data, true);
    }

    /**
     * Setter for {@link tags} property.
     *
     * @param Tag[]|array|null $data
     * @throws InvalidArgumentException
     */
    public function setTags($data) {
        return $this->populateFieldData('tags', Tag::class, $data, true, true);
    }

    /**
     * Setter for {@link analytics} property.
     *
     * @param Analytics|array|null $data
     * @throws InvalidArgumentException
     */
    public function setAnalytics($data) {
        return $this->populateFieldData('analytics', Analytics::class, $data, false, true);
    }

    /**
     * Setter for {@link organization} property.
     *
     * @param Organization|array|null $data
     * @throws InvalidArgumentException
     */
    public function setOrganization($data) {
        return $this->populateFieldData('organization', Organization::class, $data, false, true);
    }

    /**
     * Setter for {@link department} property.
     *
     * @param Department|array|null $data
     * @throws InvalidArgumentException
     */
    public function setDepartment($data) {
        return $this->populateFieldData('department', Department::class, $data, false, true);
    }

    /**
     * Setter for {@link status} property.
     *
     * @param Status|array|null $data
     * @throws InvalidArgumentException
     */
    public function setStatus($data) {
        return $this->populateFieldData('status', Status::class, $data, false, true);
    }   
}