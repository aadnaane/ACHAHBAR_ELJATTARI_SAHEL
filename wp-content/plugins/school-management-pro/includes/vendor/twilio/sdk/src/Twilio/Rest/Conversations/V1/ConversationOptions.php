<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Conversations\V1;

use Twilio\Options;
use Twilio\Values;

/**
 * PLEASE NOTE that this class contains beta products that are subject to change. Use them with caution.
 */
abstract class ConversationOptions {
    /**
     * @param string $friendlyName The human-readable name of this conversation.
     * @param \DateTime $dateCreated The date that this resource was created.
     * @param \DateTime $dateUpdated The date that this resource was last updated.
     * @param string $messagingServiceSid The unique id of the SMS Service this
     *                                    conversation belongs to.
     * @param string $attributes An optional string metadata field you can use to
     *                           store any data you wish.
     * @return CreateConversationOptions Options builder
     */
    public static function create($friendlyName = Values::NONE, $dateCreated = Values::NONE, $dateUpdated = Values::NONE, $messagingServiceSid = Values::NONE, $attributes = Values::NONE) {
        return new CreateConversationOptions($friendlyName, $dateCreated, $dateUpdated, $messagingServiceSid, $attributes);
    }

    /**
     * @param string $friendlyName The human-readable name of this conversation.
     * @param \DateTime $dateCreated The date that this resource was created.
     * @param \DateTime $dateUpdated The date that this resource was last updated.
     * @param string $attributes An optional string metadata field you can use to
     *                           store any data you wish.
     * @return UpdateConversationOptions Options builder
     */
    public static function update($friendlyName = Values::NONE, $dateCreated = Values::NONE, $dateUpdated = Values::NONE, $attributes = Values::NONE) {
        return new UpdateConversationOptions($friendlyName, $dateCreated, $dateUpdated, $attributes);
    }
}

class CreateConversationOptions extends Options {
    /**
     * @param string $friendlyName The human-readable name of this conversation.
     * @param \DateTime $dateCreated The date that this resource was created.
     * @param \DateTime $dateUpdated The date that this resource was last updated.
     * @param string $messagingServiceSid The unique id of the SMS Service this
     *                                    conversation belongs to.
     * @param string $attributes An optional string metadata field you can use to
     *                           store any data you wish.
     */
    public function __construct($friendlyName = Values::NONE, $dateCreated = Values::NONE, $dateUpdated = Values::NONE, $messagingServiceSid = Values::NONE, $attributes = Values::NONE) {
        $this->options['friendlyName'] = $friendlyName;
        $this->options['dateCreated'] = $dateCreated;
        $this->options['dateUpdated'] = $dateUpdated;
        $this->options['messagingServiceSid'] = $messagingServiceSid;
        $this->options['attributes'] = $attributes;
    }

    /**
     * The human-readable name of this conversation, limited to 256 characters. Optional.
     *
     * @param string $friendlyName The human-readable name of this conversation.
     * @return $this Fluent Builder
     */
    public function setFriendlyName($friendlyName) {
        $this->options['friendlyName'] = $friendlyName;
        return $this;
    }

    /**
     * The date that this resource was created.
     *
     * @param \DateTime $dateCreated The date that this resource was created.
     * @return $this Fluent Builder
     */
    public function setDateCreated($dateCreated) {
        $this->options['dateCreated'] = $dateCreated;
        return $this;
    }

    /**
     * The date that this resource was last updated.
     *
     * @param \DateTime $dateUpdated The date that this resource was last updated.
     * @return $this Fluent Builder
     */
    public function setDateUpdated($dateUpdated) {
        $this->options['dateUpdated'] = $dateUpdated;
        return $this;
    }

    /**
     * The unique id of the [SMS Service](https://www.twilio.com/docs/sms/services/api) this conversation belongs to.
     *
     * @param string $messagingServiceSid The unique id of the SMS Service this
     *                                    conversation belongs to.
     * @return $this Fluent Builder
     */
    public function setMessagingServiceSid($messagingServiceSid) {
        $this->options['messagingServiceSid'] = $messagingServiceSid;
        return $this;
    }

    /**
     * An optional string metadata field you can use to store any data you wish. The string value must contain structurally valid JSON if specified.  **Note** that if the attributes are not set "{}" will be returned.
     *
     * @param string $attributes An optional string metadata field you can use to
     *                           store any data you wish.
     * @return $this Fluent Builder
     */
    public function setAttributes($attributes) {
        $this->options['attributes'] = $attributes;
        return $this;
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString() {
        $options = array();
        foreach ($this->options as $key => $value) {
            if ($value != Values::NONE) {
                $options[] = "$key=$value";
            }
        }
        return '[Twilio.Conversations.V1.CreateConversationOptions ' . implode(' ', $options) . ']';
    }
}

class UpdateConversationOptions extends Options {
    /**
     * @param string $friendlyName The human-readable name of this conversation.
     * @param \DateTime $dateCreated The date that this resource was created.
     * @param \DateTime $dateUpdated The date that this resource was last updated.
     * @param string $attributes An optional string metadata field you can use to
     *                           store any data you wish.
     */
    public function __construct($friendlyName = Values::NONE, $dateCreated = Values::NONE, $dateUpdated = Values::NONE, $attributes = Values::NONE) {
        $this->options['friendlyName'] = $friendlyName;
        $this->options['dateCreated'] = $dateCreated;
        $this->options['dateUpdated'] = $dateUpdated;
        $this->options['attributes'] = $attributes;
    }

    /**
     * The human-readable name of this conversation, limited to 256 characters. Optional.
     *
     * @param string $friendlyName The human-readable name of this conversation.
     * @return $this Fluent Builder
     */
    public function setFriendlyName($friendlyName) {
        $this->options['friendlyName'] = $friendlyName;
        return $this;
    }

    /**
     * The date that this resource was created.
     *
     * @param \DateTime $dateCreated The date that this resource was created.
     * @return $this Fluent Builder
     */
    public function setDateCreated($dateCreated) {
        $this->options['dateCreated'] = $dateCreated;
        return $this;
    }

    /**
     * The date that this resource was last updated.
     *
     * @param \DateTime $dateUpdated The date that this resource was last updated.
     * @return $this Fluent Builder
     */
    public function setDateUpdated($dateUpdated) {
        $this->options['dateUpdated'] = $dateUpdated;
        return $this;
    }

    /**
     * An optional string metadata field you can use to store any data you wish. The string value must contain structurally valid JSON if specified.  **Note** that if the attributes are not set "{}" will be returned.
     *
     * @param string $attributes An optional string metadata field you can use to
     *                           store any data you wish.
     * @return $this Fluent Builder
     */
    public function setAttributes($attributes) {
        $this->options['attributes'] = $attributes;
        return $this;
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString() {
        $options = array();
        foreach ($this->options as $key => $value) {
            if ($value != Values::NONE) {
                $options[] = "$key=$value";
            }
        }
        return '[Twilio.Conversations.V1.UpdateConversationOptions ' . implode(' ', $options) . ']';
    }
}