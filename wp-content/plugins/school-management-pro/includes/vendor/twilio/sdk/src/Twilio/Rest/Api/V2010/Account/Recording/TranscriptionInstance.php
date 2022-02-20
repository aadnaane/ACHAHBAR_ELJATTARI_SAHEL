<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Api\V2010\Account\Recording;

use Twilio\Deserialize;
use Twilio\Exceptions\TwilioException;
use Twilio\InstanceResource;
use Twilio\Values;
use Twilio\Version;

/**
 * @property string $accountSid
 * @property string $apiVersion
 * @property \DateTime $dateCreated
 * @property \DateTime $dateUpdated
 * @property string $duration
 * @property string $price
 * @property string $priceUnit
 * @property string $recordingSid
 * @property string $sid
 * @property string $status
 * @property string $transcriptionText
 * @property string $type
 * @property string $uri
 */
class TranscriptionInstance extends InstanceResource {
    /**
     * Initialize the TranscriptionInstance
     *
     * @param \Twilio\Version $version Version that contains the resource
     * @param mixed[] $payload The response payload
     * @param string $accountSid The SID of the Account that created the resource
     * @param string $recordingSid The SID that identifies the transcription's
     *                             recording
     * @param string $sid The unique string that identifies the resource
     * @return \Twilio\Rest\Api\V2010\Account\Recording\TranscriptionInstance
     */
    public function __construct(Version $version, array $payload, $accountSid, $recordingSid, $sid = null) {
        parent::__construct($version);

        // Marshaled Properties
        $this->properties = array(
            'accountSid' => Values::array_get($payload, 'account_sid'),
            'apiVersion' => Values::array_get($payload, 'api_version'),
            'dateCreated' => Deserialize::dateTime(Values::array_get($payload, 'date_created')),
            'dateUpdated' => Deserialize::dateTime(Values::array_get($payload, 'date_updated')),
            'duration' => Values::array_get($payload, 'duration'),
            'price' => Values::array_get($payload, 'price'),
            'priceUnit' => Values::array_get($payload, 'price_unit'),
            'recordingSid' => Values::array_get($payload, 'recording_sid'),
            'sid' => Values::array_get($payload, 'sid'),
            'status' => Values::array_get($payload, 'status'),
            'transcriptionText' => Values::array_get($payload, 'transcription_text'),
            'type' => Values::array_get($payload, 'type'),
            'uri' => Values::array_get($payload, 'uri'),
        );

        $this->solution = array(
            'accountSid' => $accountSid,
            'recordingSid' => $recordingSid,
            'sid' => $sid ?: $this->properties['sid'],
        );
    }

    /**
     * Generate an instance context for the instance, the context is capable of
     * performing various actions.  All instance actions are proxied to the context
     *
     * @return \Twilio\Rest\Api\V2010\Account\Recording\TranscriptionContext Context for this TranscriptionInstance
     */
    protected function proxy() {
        if (!$this->context) {
            $this->context = new TranscriptionContext(
                $this->version,
                $this->solution['accountSid'],
                $this->solution['recordingSid'],
                $this->solution['sid']
            );
        }

        return $this->context;
    }

    /**
     * Fetch a TranscriptionInstance
     *
     * @return TranscriptionInstance Fetched TranscriptionInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch() {
        return $this->proxy()->fetch();
    }

    /**
     * Deletes the TranscriptionInstance
     *
     * @return boolean True if delete succeeds, false otherwise
     * @throws TwilioException When an HTTP error occurs.
     */
    public function delete() {
        return $this->proxy()->delete();
    }

    /**
     * Magic getter to access properties
     *
     * @param string $name Property to access
     * @return mixed The requested property
     * @throws TwilioException For unknown properties
     */
    public function __get($name) {
        if (array_key_exists($name, $this->properties)) {
            return $this->properties[$name];
        }

        if (property_exists($this, '_' . $name)) {
            $method = 'get' . ucfirst($name);
            return $this->$method();
        }

        throw new TwilioException('Unknown property: ' . $name);
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString() {
        $context = array();
        foreach ($this->solution as $key => $value) {
            $context[] = "$key=$value";
        }
        return '[Twilio.Api.V2010.TranscriptionInstance ' . implode(' ', $context) . ']';
    }
}