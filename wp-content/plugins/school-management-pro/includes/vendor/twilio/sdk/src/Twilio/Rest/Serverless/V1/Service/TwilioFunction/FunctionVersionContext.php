<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Serverless\V1\Service\TwilioFunction;

use Twilio\Exceptions\TwilioException;
use Twilio\InstanceContext;
use Twilio\Values;
use Twilio\Version;

/**
 * PLEASE NOTE that this class contains preview products that are subject to change. Use them with caution. If you currently do not have developer preview access, please contact help@twilio.com.
 */
class FunctionVersionContext extends InstanceContext {
    /**
     * Initialize the FunctionVersionContext
     *
     * @param \Twilio\Version $version Version that contains the resource
     * @param string $serviceSid Service Sid.
     * @param string $functionSid Function Sid.
     * @param string $sid Function Version Sid.
     * @return \Twilio\Rest\Serverless\V1\Service\TwilioFunction\FunctionVersionContext
     */
    public function __construct(Version $version, $serviceSid, $functionSid, $sid) {
        parent::__construct($version);

        // Path Solution
        $this->solution = array('serviceSid' => $serviceSid, 'functionSid' => $functionSid, 'sid' => $sid, );

        $this->uri = '/Services/' . rawurlencode($serviceSid) . '/Functions/' . rawurlencode($functionSid) . '/Versions/' . rawurlencode($sid) . '';
    }

    /**
     * Fetch a FunctionVersionInstance
     *
     * @return FunctionVersionInstance Fetched FunctionVersionInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch() {
        $params = Values::of(array());

        $payload = $this->version->fetch(
            'GET',
            $this->uri,
            $params
        );

        return new FunctionVersionInstance(
            $this->version,
            $payload,
            $this->solution['serviceSid'],
            $this->solution['functionSid'],
            $this->solution['sid']
        );
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
        return '[Twilio.Serverless.V1.FunctionVersionContext ' . implode(' ', $context) . ']';
    }
}