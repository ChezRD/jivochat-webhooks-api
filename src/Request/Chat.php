<?php

namespace ChezRD\Jivochat\Webhooks\Request;

use ChezRD\Jivochat\Webhooks\PopulateObjectViaArray;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Holds data on completed chatting (chat rank and messages list).
 *
 * @author Oleg Fedorov <olegf39@gmail.com>
 * @author Evgeny Rumiantsev <chezrd@gmail.com>
 * @package ChezRD\Jivochat\Webhooks\Request
 */
class Chat
{
    use PopulateObjectViaArray;

    /** @var ArrayCollection<Chat\Message> Messages list. See {@link ArrayCollection} {@link Chat\Message} for details. */
    public $messages;

    /** @var string|null User chat rank ("positive"|"negative"|null). */
    public $rate;

    /** @var boolean|null A sign that the user was added to the black list (e.g. false). */
    public $blacklisted;

    /** @var string|null Proactive invitation text (e.g. "Hello there!"). */
    public $invitation;

    /** @var DateTime|null Time when chat started */
    private $_chat_initiated_at;

    /** @var DateTime|null Time when agent first time responded */
    private $_chat_first_response_at;

    /** @var DateTime|null Time when chat_finished event received */
    private $_chat_finished_event_received_at;

    public function __construct() {
        $this->_chat_finished_event_received_at = new DateTime();
    }

    /**
     * Setter for {@link messages} property.
     *
     * @param array $messages
     * @throws \InvalidArgumentException
     */
    public function setMessages(array $messages) {
        $this->messages = new ArrayCollection();

        foreach ($messages as $data) {
            if ( !(is_array($data) || ($data instanceof Chat\Message)) ) {
                throw new \InvalidArgumentException('Invalid data given.');
            }

            if (is_array($data)) {
                $message = new Chat\Message();
                $message->populate($data);
                $this->messages->add($message);
                continue;
            }

            if ($data instanceof Chat\Message) {
                $this->messages->add($data);
                continue;
            }
        }

        if ( !$this->messages->isEmpty() ) {
            /** @var Chat\Message */
            $first = $this->messages->first();

            /** @var Chat\Message */
            $first_agent_response = $this->messages
                ->filter(function(Chat\Message $element) {
                    return $element->type === 'agent';
                })
                ->first();

            $this->_chat_initiated_at = new DateTime();
            $this->_chat_initiated_at->setTimestamp($first->timestamp);

            $this->_chat_first_response_at = new DateTime();
            $this->_chat_first_response_at->setTimestamp($first_agent_response->timestamp);
        } else {
            $this->_chat_initiated_at = null;
            $this->_chat_first_response_at = null;
        }
    }

    public function timeOfFirstMessage() {
        return $this->_chat_initiated_at;
    }

    public function timeOfFirstAgentResponse() {
        return $this->_chat_first_response_at;
    }

    public function timeOfChatFinishedEvent() {
        return $this->_chat_finished_event_received_at;
    }
}