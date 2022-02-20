<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Preview\Marketplace\AvailableAddOn;

use Twilio\Exceptions\TwilioException;
use Twilio\InstanceResource;
use Twilio\Values;
use Twilio\Version;

/**
 * PLEASE NOTE that this class contains preview products that are subject to change. Use them with caution. If you currently do not have developer preview access, please contact help@twilio.com.
 *
 * @property string $sid
 * @property string $availableAddOnSid
 * @property string $friendlyName
 * @property string $productName
 * @property string $uniqueName
 * @property string $url
 */
class AvailableAddOnExtensionInstance extends InstanceResource {
    /**
     * Initialize the AvailableAddOnExtensionInstance
     *
     * @param \Twilio\Version $version Version that contains the resource
     * @param mixed[] $payload The response payload
     * @param string $availableAddOnSid The available_add_on_sid
     * @param string $sid The unique Extension Sid
     * @return \Twilio\Rest\Preview\Marketplace\AvailableAddOn\AvailableAddOnExtensionInstance
     */
    public function __construct(Version $version, array $payload, $availableAddOnSid, $sid = null) {
        parent::__construct($version);

        // Marshaled Properties
        $this->properties = array(
            'sid' => Values::array_get($payload, 'sid'),
            'availableAddOnSid' => Values::array_get($payload, 'available_add_on_sid'),
            'friendlyName' => Values::array_get($payload, 'friendly_name'),
            'productName' => Values::array_get($payload, 'product_name'),
            'uniqueName' => Values::array_get($payload, 'unique_name'),
            'url' => Values::array_get($payload, 'url'),
        );

        $this->solution = array(
            'availableAddOnSid' => $availableAddOnSid,
            'sid' => $sid ?: $this->properties['sid'],
        );
    }

    /**
     * Generate an instance context for the instance, the context is capable of
     * performing various actions.  All instance actions are proxied to the context
     *
     * @return \Twilio\Rest\Preview\Marketplace\AvailableAddOn\AvailableAddOnExtensionContext Context for this
     *                                                                                        AvailableAddOnExtensionInstance
     */
    protected function proxy() {
        if (!$this->context) {
            $this->context = new AvailableAddOnExtensionContext(
                $this->version,
                $this->solution['availableAddOnSid'],
                $this->solution['sid']
            );
        }

        return $this->context;
    }

    /**
     * Fetch a AvailableAddOnExtensionInstance
     *
     * @return AvailableAddOnExtensionInstance Fetched
     *                                         AvailableAddOnExtensionInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch() {
        return $this->proxy()->fetch();
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
        return '[Twilio.Preview.Marketplace.AvailableAddOnExtensionInstance ' . implode(' ', $context) . ']';
    }
}