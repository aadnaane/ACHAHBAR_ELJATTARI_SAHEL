<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Sync\V1\Service\Document;

use Twilio\Exceptions\TwilioException;
use Twilio\InstanceContext;
use Twilio\Serialize;
use Twilio\Values;
use Twilio\Version;

/**
 * PLEASE NOTE that this class contains beta products that are subject to change. Use them with caution.
 */
class DocumentPermissionContext extends InstanceContext {
    /**
     * Initialize the DocumentPermissionContext
     *
     * @param \Twilio\Version $version Version that contains the resource
     * @param string $serviceSid The SID of the Sync Service with the Document
     *                           Permission resource to fetch
     * @param string $documentSid The SID of the Sync Document with the Document
     *                            Permission resource to fetch
     * @param string $identity The application-defined string that uniquely
     *                         identifies the User's Document Permission resource
     *                         to fetch
     * @return \Twilio\Rest\Sync\V1\Service\Document\DocumentPermissionContext
     */
    public function __construct(Version $version, $serviceSid, $documentSid, $identity) {
        parent::__construct($version);

        // Path Solution
        $this->solution = array(
            'serviceSid' => $serviceSid,
            'documentSid' => $documentSid,
            'identity' => $identity,
        );

        $this->uri = '/Services/' . rawurlencode($serviceSid) . '/Documents/' . rawurlencode($documentSid) . '/Permissions/' . rawurlencode($identity) . '';
    }

    /**
     * Fetch a DocumentPermissionInstance
     *
     * @return DocumentPermissionInstance Fetched DocumentPermissionInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch() {
        $params = Values::of(array());

        $payload = $this->version->fetch(
            'GET',
            $this->uri,
            $params
        );

        return new DocumentPermissionInstance(
            $this->version,
            $payload,
            $this->solution['serviceSid'],
            $this->solution['documentSid'],
            $this->solution['identity']
        );
    }

    /**
     * Deletes the DocumentPermissionInstance
     *
     * @return boolean True if delete succeeds, false otherwise
     * @throws TwilioException When an HTTP error occurs.
     */
    public function delete() {
        return $this->version->delete('delete', $this->uri);
    }

    /**
     * Update the DocumentPermissionInstance
     *
     * @param bool $read Read access
     * @param bool $write Write access
     * @param bool $manage Manage access
     * @return DocumentPermissionInstance Updated DocumentPermissionInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function update($read, $write, $manage) {
        $data = Values::of(array(
            'Read' => Serialize::booleanToString($read),
            'Write' => Serialize::booleanToString($write),
            'Manage' => Serialize::booleanToString($manage),
        ));

        $payload = $this->version->update(
            'POST',
            $this->uri,
            array(),
            $data
        );

        return new DocumentPermissionInstance(
            $this->version,
            $payload,
            $this->solution['serviceSid'],
            $this->solution['documentSid'],
            $this->solution['identity']
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
        return '[Twilio.Sync.V1.DocumentPermissionContext ' . implode(' ', $context) . ']';
    }
}