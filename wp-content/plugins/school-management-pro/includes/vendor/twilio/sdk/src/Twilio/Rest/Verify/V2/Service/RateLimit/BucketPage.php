<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Verify\V2\Service\RateLimit;

use Twilio\Page;

/**
 * PLEASE NOTE that this class contains beta products that are subject to change. Use them with caution.
 */
class BucketPage extends Page {
    public function __construct($version, $response, $solution) {
        parent::__construct($version, $response);

        // Path Solution
        $this->solution = $solution;
    }

    public function buildInstance(array $payload) {
        return new BucketInstance(
            $this->version,
            $payload,
            $this->solution['serviceSid'],
            $this->solution['rateLimitSid']
        );
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString() {
        return '[Twilio.Verify.V2.BucketPage]';
    }
}