<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\TwiML\Messaging;

use Twilio\TwiML\TwiML;

class Message extends TwiML {
    /**
     * Message constructor.
     *
     * @param string $body Message Body
     * @param array $attributes Optional attributes
     */
    public function __construct($body, $attributes = array()) {
        parent::__construct('Message', $body, $attributes);
    }

    /**
     * Add Body child.
     *
     * @param string $message Message Body
     * @return Body Child element.
     */
    public function body($message) {
        return $this->nest(new Body($message));
    }

    /**
     * Add Media child.
     *
     * @param string $url Media URL
     * @return Media Child element.
     */
    public function media($url) {
        return $this->nest(new Media($url));
    }

    /**
     * Add To attribute.
     *
     * @param string $to Phone Number to send Message to
     * @return static $this.
     */
    public function setTo($to) {
        return $this->setAttribute('to', $to);
    }

    /**
     * Add From attribute.
     *
     * @param string $from Phone Number to send Message from
     * @return static $this.
     */
    public function setFrom($from) {
        return $this->setAttribute('from', $from);
    }

    /**
     * Add Action attribute.
     *
     * @param string $action Action URL
     * @return static $this.
     */
    public function setAction($action) {
        return $this->setAttribute('action', $action);
    }

    /**
     * Add Method attribute.
     *
     * @param string $method Action URL Method
     * @return static $this.
     */
    public function setMethod($method) {
        return $this->setAttribute('method', $method);
    }

    /**
     * Add StatusCallback attribute.
     *
     * @param string $statusCallback Status callback URL. Deprecated in favor of
     *                               action.
     * @return static $this.
     */
    public function setStatusCallback($statusCallback) {
        return $this->setAttribute('statusCallback', $statusCallback);
    }
}