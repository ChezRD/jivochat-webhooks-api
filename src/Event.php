<?php

namespace ChezRD\Jivochat\Webhooks;

use ChezRD\Jivochat\Webhooks\Event\CallEvent;
use ChezRD\Jivochat\Webhooks\Event\ChatAccepted;
use ChezRD\Jivochat\Webhooks\Event\ChatAssigned;
use ChezRD\Jivochat\Webhooks\Event\ChatFinished;
use ChezRD\Jivochat\Webhooks\Event\ChatUpdated;
use ChezRD\Jivochat\Webhooks\Event\ClientUpdated;
use ChezRD\Jivochat\Webhooks\Event\OfflineMessage;
use ChezRD\Jivochat\Webhooks\Model\EventRequest\CallEventRequest;
use ChezRD\Jivochat\Webhooks\Model\EventRequest\ChatAcceptedRequest;
use ChezRD\Jivochat\Webhooks\Model\EventRequest\ChatAssignedRequest;
use ChezRD\Jivochat\Webhooks\Model\EventRequest\ChatFinishedRequest;
use ChezRD\Jivochat\Webhooks\Model\EventRequest\ChatUpdatedRequest;
use ChezRD\Jivochat\Webhooks\Model\EventRequest\ClientUpdatedRequest;
use ChezRD\Jivochat\Webhooks\Model\EventRequest\OfflineMessageRequest;
use ChezRD\Jivochat\Webhooks\Model\Request;
use InvalidArgumentException;
use ReturnTypeWillChange;

/**
 * Class Event
 *
 * @author Oleg Fedorov <olegf39@gmail.com>
 * @author Evgeny Rumiantsev <chezrd@gmail.com>
 * @package ChezRD\Jivochat\Webhooks\Event
 */
abstract class Event
{
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

    /** @var Request Structured data from current request */
    protected Request $request;

    #[ReturnTypeWillChange]
    public function getRequest(): Request {
        return $this->request;
    }

    /**
     * Event constructor.
     *
     * @param Request $request
     */
    final protected function __construct(Request $request)
    {
        $this->request = $request;
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
                return new CallEvent(new CallEventRequest($decodedRequest));
                break;
            case static::EVENT_CLIENT_UPDATED:
                return new ClientUpdated(new ClientUpdatedRequest($decodedRequest));
                break;
            case static::EVENT_CHAT_ACCEPTED:
                return new ChatAccepted(new ChatAcceptedRequest($decodedRequest));
                break;
            case static::EVENT_CHAT_ASSIGNED:
                return new ChatAssigned(new ChatAssignedRequest($decodedRequest));
                break;
            case static::EVENT_CHAT_UPDATED:
                return new ChatUpdated(new ChatUpdatedRequest($decodedRequest));
                break;
            case static::EVENT_CHAT_FINISHED:
                return new ChatFinished(new ChatFinishedRequest($decodedRequest));
                break;
            case static::EVENT_OFFLINE_MESSAGE:
                return new OfflineMessage(new OfflineMessageRequest($decodedRequest));
                break;
            default:
                throw new \LogicException("Request class for event name `{$eventName}` is not implemented.");
                break;
        }
    }
}