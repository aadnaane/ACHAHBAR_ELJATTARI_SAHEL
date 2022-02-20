<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Preview\Understand;

use Twilio\Options;
use Twilio\Values;

/**
 * PLEASE NOTE that this class contains preview products that are subject to change. Use them with caution. If you currently do not have developer preview access, please contact help@twilio.com.
 */
abstract class AssistantOptions {
    /**
     * @param string $friendlyName A text description for the Assistant. It is
     *                             non-unique and can up to 255 characters long.
     * @param bool $logQueries A boolean that specifies whether queries should be
     *                         logged for 30 days further training. If false, no
     *                         queries will be stored, if true, queries will be
     *                         stored for 30 days and deleted thereafter. Defaults
     *                         to true if no value is provided.
     * @param string $uniqueName A user-provided string that uniquely identifies
     *                           this resource as an alternative to the sid. Unique
     *                           up to 64 characters long.
     * @param string $callbackUrl A user-provided URL to send event callbacks to.
     * @param string $callbackEvents Space-separated list of callback events that
     *                               will trigger callbacks.
     * @param array $fallbackActions The JSON actions to be executed when the
     *                               user's input is not recognized as matching any
     *                               Task.
     * @param array $initiationActions The JSON actions to be executed on inbound
     *                                 phone calls when the Assistant has to say
     *                                 something first.
     * @param array $styleSheet The JSON object that holds the style sheet for the
     *                          assistant
     * @return CreateAssistantOptions Options builder
     */
    public static function create($friendlyName = Values::NONE, $logQueries = Values::NONE, $uniqueName = Values::NONE, $callbackUrl = Values::NONE, $callbackEvents = Values::NONE, $fallbackActions = Values::NONE, $initiationActions = Values::NONE, $styleSheet = Values::NONE) {
        return new CreateAssistantOptions($friendlyName, $logQueries, $uniqueName, $callbackUrl, $callbackEvents, $fallbackActions, $initiationActions, $styleSheet);
    }

    /**
     * @param string $friendlyName A text description for the Assistant. It is
     *                             non-unique and can up to 255 characters long.
     * @param bool $logQueries A boolean that specifies whether queries should be
     *                         logged for 30 days further training. If false, no
     *                         queries will be stored, if true, queries will be
     *                         stored for 30 days and deleted thereafter. Defaults
     *                         to true if no value is provided.
     * @param string $uniqueName A user-provided string that uniquely identifies
     *                           this resource as an alternative to the sid. Unique
     *                           up to 64 characters long.
     * @param string $callbackUrl A user-provided URL to send event callbacks to.
     * @param string $callbackEvents Space-separated list of callback events that
     *                               will trigger callbacks.
     * @param array $fallbackActions The JSON actions to be executed when the
     *                               user's input is not recognized as matching any
     *                               Task.
     * @param array $initiationActions The JSON actions to be executed on inbound
     *                                 phone calls when the Assistant has to say
     *                                 something first.
     * @param array $styleSheet The JSON object that holds the style sheet for the
     *                          assistant
     * @return UpdateAssistantOptions Options builder
     */
    public static function update($friendlyName = Values::NONE, $logQueries = Values::NONE, $uniqueName = Values::NONE, $callbackUrl = Values::NONE, $callbackEvents = Values::NONE, $fallbackActions = Values::NONE, $initiationActions = Values::NONE, $styleSheet = Values::NONE) {
        return new UpdateAssistantOptions($friendlyName, $logQueries, $uniqueName, $callbackUrl, $callbackEvents, $fallbackActions, $initiationActions, $styleSheet);
    }
}

class CreateAssistantOptions extends Options {
    /**
     * @param string $friendlyName A text description for the Assistant. It is
     *                             non-unique and can up to 255 characters long.
     * @param bool $logQueries A boolean that specifies whether queries should be
     *                         logged for 30 days further training. If false, no
     *                         queries will be stored, if true, queries will be
     *                         stored for 30 days and deleted thereafter. Defaults
     *                         to true if no value is provided.
     * @param string $uniqueName A user-provided string that uniquely identifies
     *                           this resource as an alternative to the sid. Unique
     *                           up to 64 characters long.
     * @param string $callbackUrl A user-provided URL to send event callbacks to.
     * @param string $callbackEvents Space-separated list of callback events that
     *                               will trigger callbacks.
     * @param array $fallbackActions The JSON actions to be executed when the
     *                               user's input is not recognized as matching any
     *                               Task.
     * @param array $initiationActions The JSON actions to be executed on inbound
     *                                 phone calls when the Assistant has to say
     *                                 something first.
     * @param array $styleSheet The JSON object that holds the style sheet for the
     *                          assistant
     */
    public function __construct($friendlyName = Values::NONE, $logQueries = Values::NONE, $uniqueName = Values::NONE, $callbackUrl = Values::NONE, $callbackEvents = Values::NONE, $fallbackActions = Values::NONE, $initiationActions = Values::NONE, $styleSheet = Values::NONE) {
        $this->options['friendlyName'] = $friendlyName;
        $this->options['logQueries'] = $logQueries;
        $this->options['uniqueName'] = $uniqueName;
        $this->options['callbackUrl'] = $callbackUrl;
        $this->options['callbackEvents'] = $callbackEvents;
        $this->options['fallbackActions'] = $fallbackActions;
        $this->options['initiationActions'] = $initiationActions;
        $this->options['styleSheet'] = $styleSheet;
    }

    /**
     * A text description for the Assistant. It is non-unique and can up to 255 characters long.
     *
     * @param string $friendlyName A text description for the Assistant. It is
     *                             non-unique and can up to 255 characters long.
     * @return $this Fluent Builder
     */
    public function setFriendlyName($friendlyName) {
        $this->options['friendlyName'] = $friendlyName;
        return $this;
    }

    /**
     * A boolean that specifies whether queries should be logged for 30 days further training. If false, no queries will be stored, if true, queries will be stored for 30 days and deleted thereafter. Defaults to true if no value is provided.
     *
     * @param bool $logQueries A boolean that specifies whether queries should be
     *                         logged for 30 days further training. If false, no
     *                         queries will be stored, if true, queries will be
     *                         stored for 30 days and deleted thereafter. Defaults
     *                         to true if no value is provided.
     * @return $this Fluent Builder
     */
    public function setLogQueries($logQueries) {
        $this->options['logQueries'] = $logQueries;
        return $this;
    }

    /**
     * A user-provided string that uniquely identifies this resource as an alternative to the sid. Unique up to 64 characters long.
     *
     * @param string $uniqueName A user-provided string that uniquely identifies
     *                           this resource as an alternative to the sid. Unique
     *                           up to 64 characters long.
     * @return $this Fluent Builder
     */
    public function setUniqueName($uniqueName) {
        $this->options['uniqueName'] = $uniqueName;
        return $this;
    }

    /**
     * A user-provided URL to send event callbacks to.
     *
     * @param string $callbackUrl A user-provided URL to send event callbacks to.
     * @return $this Fluent Builder
     */
    public function setCallbackUrl($callbackUrl) {
        $this->options['callbackUrl'] = $callbackUrl;
        return $this;
    }

    /**
     * Space-separated list of callback events that will trigger callbacks.
     *
     * @param string $callbackEvents Space-separated list of callback events that
     *                               will trigger callbacks.
     * @return $this Fluent Builder
     */
    public function setCallbackEvents($callbackEvents) {
        $this->options['callbackEvents'] = $callbackEvents;
        return $this;
    }

    /**
     * The JSON actions to be executed when the user's input is not recognized as matching any Task.
     *
     * @param array $fallbackActions The JSON actions to be executed when the
     *                               user's input is not recognized as matching any
     *                               Task.
     * @return $this Fluent Builder
     */
    public function setFallbackActions($fallbackActions) {
        $this->options['fallbackActions'] = $fallbackActions;
        return $this;
    }

    /**
     * The JSON actions to be executed on inbound phone calls when the Assistant has to say something first.
     *
     * @param array $initiationActions The JSON actions to be executed on inbound
     *                                 phone calls when the Assistant has to say
     *                                 something first.
     * @return $this Fluent Builder
     */
    public function setInitiationActions($initiationActions) {
        $this->options['initiationActions'] = $initiationActions;
        return $this;
    }

    /**
     * The JSON object that holds the style sheet for the assistant
     *
     * @param array $styleSheet The JSON object that holds the style sheet for the
     *                          assistant
     * @return $this Fluent Builder
     */
    public function setStyleSheet($styleSheet) {
        $this->options['styleSheet'] = $styleSheet;
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
        return '[Twilio.Preview.Understand.CreateAssistantOptions ' . implode(' ', $options) . ']';
    }
}

class UpdateAssistantOptions extends Options {
    /**
     * @param string $friendlyName A text description for the Assistant. It is
     *                             non-unique and can up to 255 characters long.
     * @param bool $logQueries A boolean that specifies whether queries should be
     *                         logged for 30 days further training. If false, no
     *                         queries will be stored, if true, queries will be
     *                         stored for 30 days and deleted thereafter. Defaults
     *                         to true if no value is provided.
     * @param string $uniqueName A user-provided string that uniquely identifies
     *                           this resource as an alternative to the sid. Unique
     *                           up to 64 characters long.
     * @param string $callbackUrl A user-provided URL to send event callbacks to.
     * @param string $callbackEvents Space-separated list of callback events that
     *                               will trigger callbacks.
     * @param array $fallbackActions The JSON actions to be executed when the
     *                               user's input is not recognized as matching any
     *                               Task.
     * @param array $initiationActions The JSON actions to be executed on inbound
     *                                 phone calls when the Assistant has to say
     *                                 something first.
     * @param array $styleSheet The JSON object that holds the style sheet for the
     *                          assistant
     */
    public function __construct($friendlyName = Values::NONE, $logQueries = Values::NONE, $uniqueName = Values::NONE, $callbackUrl = Values::NONE, $callbackEvents = Values::NONE, $fallbackActions = Values::NONE, $initiationActions = Values::NONE, $styleSheet = Values::NONE) {
        $this->options['friendlyName'] = $friendlyName;
        $this->options['logQueries'] = $logQueries;
        $this->options['uniqueName'] = $uniqueName;
        $this->options['callbackUrl'] = $callbackUrl;
        $this->options['callbackEvents'] = $callbackEvents;
        $this->options['fallbackActions'] = $fallbackActions;
        $this->options['initiationActions'] = $initiationActions;
        $this->options['styleSheet'] = $styleSheet;
    }

    /**
     * A text description for the Assistant. It is non-unique and can up to 255 characters long.
     *
     * @param string $friendlyName A text description for the Assistant. It is
     *                             non-unique and can up to 255 characters long.
     * @return $this Fluent Builder
     */
    public function setFriendlyName($friendlyName) {
        $this->options['friendlyName'] = $friendlyName;
        return $this;
    }

    /**
     * A boolean that specifies whether queries should be logged for 30 days further training. If false, no queries will be stored, if true, queries will be stored for 30 days and deleted thereafter. Defaults to true if no value is provided.
     *
     * @param bool $logQueries A boolean that specifies whether queries should be
     *                         logged for 30 days further training. If false, no
     *                         queries will be stored, if true, queries will be
     *                         stored for 30 days and deleted thereafter. Defaults
     *                         to true if no value is provided.
     * @return $this Fluent Builder
     */
    public function setLogQueries($logQueries) {
        $this->options['logQueries'] = $logQueries;
        return $this;
    }

    /**
     * A user-provided string that uniquely identifies this resource as an alternative to the sid. Unique up to 64 characters long.
     *
     * @param string $uniqueName A user-provided string that uniquely identifies
     *                           this resource as an alternative to the sid. Unique
     *                           up to 64 characters long.
     * @return $this Fluent Builder
     */
    public function setUniqueName($uniqueName) {
        $this->options['uniqueName'] = $uniqueName;
        return $this;
    }

    /**
     * A user-provided URL to send event callbacks to.
     *
     * @param string $callbackUrl A user-provided URL to send event callbacks to.
     * @return $this Fluent Builder
     */
    public function setCallbackUrl($callbackUrl) {
        $this->options['callbackUrl'] = $callbackUrl;
        return $this;
    }

    /**
     * Space-separated list of callback events that will trigger callbacks.
     *
     * @param string $callbackEvents Space-separated list of callback events that
     *                               will trigger callbacks.
     * @return $this Fluent Builder
     */
    public function setCallbackEvents($callbackEvents) {
        $this->options['callbackEvents'] = $callbackEvents;
        return $this;
    }

    /**
     * The JSON actions to be executed when the user's input is not recognized as matching any Task.
     *
     * @param array $fallbackActions The JSON actions to be executed when the
     *                               user's input is not recognized as matching any
     *                               Task.
     * @return $this Fluent Builder
     */
    public function setFallbackActions($fallbackActions) {
        $this->options['fallbackActions'] = $fallbackActions;
        return $this;
    }

    /**
     * The JSON actions to be executed on inbound phone calls when the Assistant has to say something first.
     *
     * @param array $initiationActions The JSON actions to be executed on inbound
     *                                 phone calls when the Assistant has to say
     *                                 something first.
     * @return $this Fluent Builder
     */
    public function setInitiationActions($initiationActions) {
        $this->options['initiationActions'] = $initiationActions;
        return $this;
    }

    /**
     * The JSON object that holds the style sheet for the assistant
     *
     * @param array $styleSheet The JSON object that holds the style sheet for the
     *                          assistant
     * @return $this Fluent Builder
     */
    public function setStyleSheet($styleSheet) {
        $this->options['styleSheet'] = $styleSheet;
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
        return '[Twilio.Preview.Understand.UpdateAssistantOptions ' . implode(' ', $options) . ']';
    }
}