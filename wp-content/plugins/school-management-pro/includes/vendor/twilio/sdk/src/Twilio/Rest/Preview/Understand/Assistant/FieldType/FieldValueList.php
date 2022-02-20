<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Preview\Understand\Assistant\FieldType;

use Twilio\Exceptions\TwilioException;
use Twilio\ListResource;
use Twilio\Options;
use Twilio\Values;
use Twilio\Version;

/**
 * PLEASE NOTE that this class contains preview products that are subject to change. Use them with caution. If you currently do not have developer preview access, please contact help@twilio.com.
 */
class FieldValueList extends ListResource {
    /**
     * Construct the FieldValueList
     *
     * @param Version $version Version that contains the resource
     * @param string $assistantSid The unique ID of the Assistant.
     * @param string $fieldTypeSid The unique ID of the Field Type associated with
     *                             this Field Value.
     * @return \Twilio\Rest\Preview\Understand\Assistant\FieldType\FieldValueList
     */
    public function __construct(Version $version, $assistantSid, $fieldTypeSid) {
        parent::__construct($version);

        // Path Solution
        $this->solution = array('assistantSid' => $assistantSid, 'fieldTypeSid' => $fieldTypeSid, );

        $this->uri = '/Assistants/' . rawurlencode($assistantSid) . '/FieldTypes/' . rawurlencode($fieldTypeSid) . '/FieldValues';
    }

    /**
     * Streams FieldValueInstance records from the API as a generator stream.
     * This operation lazily loads records as efficiently as possible until the
     * limit
     * is reached.
     * The results are returned as a generator, so this operation is memory
     * efficient.
     *
     * @param array|Options $options Optional Arguments
     * @param int $limit Upper limit for the number of records to return. stream()
     *                   guarantees to never return more than limit.  Default is no
     *                   limit
     * @param mixed $pageSize Number of records to fetch per request, when not set
     *                        will use the default value of 50 records.  If no
     *                        page_size is defined but a limit is defined, stream()
     *                        will attempt to read the limit with the most
     *                        efficient page size, i.e. min(limit, 1000)
     * @return \Twilio\Stream stream of results
     */
    public function stream($options = array(), $limit = null, $pageSize = null) {
        $limits = $this->version->readLimits($limit, $pageSize);

        $page = $this->page($options, $limits['pageSize']);

        return $this->version->stream($page, $limits['limit'], $limits['pageLimit']);
    }

    /**
     * Reads FieldValueInstance records from the API as a list.
     * Unlike stream(), this operation is eager and will load `limit` records into
     * memory before returning.
     *
     * @param array|Options $options Optional Arguments
     * @param int $limit Upper limit for the number of records to return. read()
     *                   guarantees to never return more than limit.  Default is no
     *                   limit
     * @param mixed $pageSize Number of records to fetch per request, when not set
     *                        will use the default value of 50 records.  If no
     *                        page_size is defined but a limit is defined, read()
     *                        will attempt to read the limit with the most
     *                        efficient page size, i.e. min(limit, 1000)
     * @return FieldValueInstance[] Array of results
     */
    public function read($options = array(), $limit = null, $pageSize = null) {
        return iterator_to_array($this->stream($options, $limit, $pageSize), false);
    }

    /**
     * Retrieve a single page of FieldValueInstance records from the API.
     * Request is executed immediately
     *
     * @param array|Options $options Optional Arguments
     * @param mixed $pageSize Number of records to return, defaults to 50
     * @param string $pageToken PageToken provided by the API
     * @param mixed $pageNumber Page Number, this value is simply for client state
     * @return \Twilio\Page Page of FieldValueInstance
     */
    public function page($options = array(), $pageSize = Values::NONE, $pageToken = Values::NONE, $pageNumber = Values::NONE) {
        $options = new Values($options);
        $params = Values::of(array(
            'Language' => $options['language'],
            'PageToken' => $pageToken,
            'Page' => $pageNumber,
            'PageSize' => $pageSize,
        ));

        $response = $this->version->page(
            'GET',
            $this->uri,
            $params
        );

        return new FieldValuePage($this->version, $response, $this->solution);
    }

    /**
     * Retrieve a specific page of FieldValueInstance records from the API.
     * Request is executed immediately
     *
     * @param string $targetUrl API-generated URL for the requested results page
     * @return \Twilio\Page Page of FieldValueInstance
     */
    public function getPage($targetUrl) {
        $response = $this->version->getDomain()->getClient()->request(
            'GET',
            $targetUrl
        );

        return new FieldValuePage($this->version, $response, $this->solution);
    }

    /**
     * Create a new FieldValueInstance
     *
     * @param string $language An ISO language-country string of the value.
     * @param string $value A user-provided string that uniquely identifies this
     *                      resource as an alternative to the sid. Unique up to 64
     *                      characters long.
     * @param array|Options $options Optional Arguments
     * @return FieldValueInstance Newly created FieldValueInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function create($language, $value, $options = array()) {
        $options = new Values($options);

        $data = Values::of(array(
            'Language' => $language,
            'Value' => $value,
            'SynonymOf' => $options['synonymOf'],
        ));

        $payload = $this->version->create(
            'POST',
            $this->uri,
            array(),
            $data
        );

        return new FieldValueInstance(
            $this->version,
            $payload,
            $this->solution['assistantSid'],
            $this->solution['fieldTypeSid']
        );
    }

    /**
     * Constructs a FieldValueContext
     *
     * @param string $sid The sid
     * @return \Twilio\Rest\Preview\Understand\Assistant\FieldType\FieldValueContext
     */
    public function getContext($sid) {
        return new FieldValueContext(
            $this->version,
            $this->solution['assistantSid'],
            $this->solution['fieldTypeSid'],
            $sid
        );
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString() {
        return '[Twilio.Preview.Understand.FieldValueList]';
    }
}