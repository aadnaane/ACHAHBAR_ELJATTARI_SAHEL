<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Sync\V1\Service\SyncMap;

use Twilio\Deserialize;
use Twilio\Exceptions\TwilioException;
use Twilio\InstanceResource;
use Twilio\Options;
use Twilio\Values;
use Twilio\Version;

/**
 * PLEASE NOTE that this class contains beta products that are subject to change. Use them with caution.
 *
 * @property string $key
 * @property string $accountSid
 * @property string $serviceSid
 * @property string $mapSid
 * @property string $url
 * @property string $revision
 * @property array $data
 * @property \DateTime $dateExpires
 * @property \DateTime $dateCreated
 * @property \DateTime $dateUpdated
 * @property string $createdBy
 */
class SyncMapItemInstance extends InstanceResource {
    /**
     * Initialize the SyncMapItemInstance
     *
     * @param \Twilio\Version $version Version that contains the resource
     * @param mixed[] $payload The response payload
     * @param string $serviceSid The SID of the Sync Service that the resource is
     *                           associated with
     * @param string $mapSid The SID of the Sync Map that contains the Map Item
     * @param string $key The key value of the Sync Map Item resource to fetch
     * @return \Twilio\Rest\Sync\V1\Service\SyncMap\SyncMapItemInstance
     */
    public function __construct(Version $version, array $payload, $serviceSid, $mapSid, $key = null) {
        parent::__construct($version);

        // Marshaled Properties
        $this->properties = array(
            'key' => Values::array_get($payload, 'key'),
            'accountSid' => Values::array_get($payload, 'account_sid'),
            'serviceSid' => Values::array_get($payload, 'service_sid'),
            'mapSid' => Values::array_get($payload, 'map_sid'),
            'url' => Values::array_get($payload, 'url'),
            'revision' => Values::array_get($payload, 'revision'),
            'data' => Values::array_get($payload, 'data'),
            'dateExpires' => Deserialize::dateTime(Values::array_get($payload, 'date_expires')),
            'dateCreated' => Deserialize::dateTime(Values::array_get($payload, 'date_created')),
            'dateUpdated' => Deserialize::dateTime(Values::array_get($payload, 'date_updated')),
            'createdBy' => Values::array_get($payload, 'created_by'),
        );

        $this->solution = array(
            'serviceSid' => $serviceSid,
            'mapSid' => $mapSid,
            'key' => $key ?: $this->properties['key'],
        );
    }

    /**
     * Generate an instance context for the instance, the context is capable of
     * performing various actions.  All instance actions are proxied to the context
     *
     * @return \Twilio\Rest\Sync\V1\Service\SyncMap\SyncMapItemContext Context for
     *                                                                 this
     *                                                                 SyncMapItemInstance
     */
    protected function proxy() {
        if (!$this->context) {
            $this->context = new SyncMapItemContext(
                $this->version,
                $this->solution['serviceSid'],
                $this->solution['mapSid'],
                $this->solution['key']
            );
        }

        return $this->context;
    }

    /**
     * Fetch a SyncMapItemInstance
     *
     * @return SyncMapItemInstance Fetched SyncMapItemInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch() {
        return $this->proxy()->fetch();
    }

    /**
     * Deletes the SyncMapItemInstance
     *
     * @return boolean True if delete succeeds, false otherwise
     * @throws TwilioException When an HTTP error occurs.
     */
    public function delete() {
        return $this->proxy()->delete();
    }

    /**
     * Update the SyncMapItemInstance
     *
     * @param array|Options $options Optional Arguments
     * @return SyncMapItemInstance Updated SyncMapItemInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function update($options = array()) {
        return $this->proxy()->update($options);
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
        return '[Twilio.Sync.V1.SyncMapItemInstance ' . implode(' ', $context) . ']';
    }
}