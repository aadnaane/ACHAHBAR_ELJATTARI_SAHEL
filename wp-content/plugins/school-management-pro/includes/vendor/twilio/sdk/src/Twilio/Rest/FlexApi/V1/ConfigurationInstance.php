<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\FlexApi\V1;

use Twilio\Deserialize;
use Twilio\Exceptions\TwilioException;
use Twilio\InstanceResource;
use Twilio\Options;
use Twilio\Values;
use Twilio\Version;

/**
 * @property string $accountSid
 * @property \DateTime $dateCreated
 * @property \DateTime $dateUpdated
 * @property array $attributes
 * @property string $status
 * @property string $taskrouterWorkspaceSid
 * @property string $taskrouterTargetWorkflowSid
 * @property string $taskrouterTargetTaskqueueSid
 * @property array $taskrouterTaskqueues
 * @property array $taskrouterSkills
 * @property array $taskrouterWorkerChannels
 * @property array $taskrouterWorkerAttributes
 * @property string $taskrouterOfflineActivitySid
 * @property string $runtimeDomain
 * @property string $messagingServiceInstanceSid
 * @property string $chatServiceInstanceSid
 * @property string $uiLanguage
 * @property array $uiAttributes
 * @property string $uiVersion
 * @property string $serviceVersion
 * @property bool $callRecordingEnabled
 * @property string $callRecordingWebhookUrl
 * @property bool $crmEnabled
 * @property string $crmType
 * @property string $crmCallbackUrl
 * @property string $crmFallbackUrl
 * @property array $crmAttributes
 * @property array $publicAttributes
 * @property bool $pluginServiceEnabled
 * @property array $pluginServiceAttributes
 * @property array $integrations
 * @property array $outboundCallFlows
 * @property string $featuresEnabled
 * @property string $serverlessServiceSids
 * @property string $url
 */
class ConfigurationInstance extends InstanceResource {
    /**
     * Initialize the ConfigurationInstance
     *
     * @param \Twilio\Version $version Version that contains the resource
     * @param mixed[] $payload The response payload
     * @return \Twilio\Rest\FlexApi\V1\ConfigurationInstance
     */
    public function __construct(Version $version, array $payload) {
        parent::__construct($version);

        // Marshaled Properties
        $this->properties = array(
            'accountSid' => Values::array_get($payload, 'account_sid'),
            'dateCreated' => Deserialize::dateTime(Values::array_get($payload, 'date_created')),
            'dateUpdated' => Deserialize::dateTime(Values::array_get($payload, 'date_updated')),
            'attributes' => Values::array_get($payload, 'attributes'),
            'status' => Values::array_get($payload, 'status'),
            'taskrouterWorkspaceSid' => Values::array_get($payload, 'taskrouter_workspace_sid'),
            'taskrouterTargetWorkflowSid' => Values::array_get($payload, 'taskrouter_target_workflow_sid'),
            'taskrouterTargetTaskqueueSid' => Values::array_get($payload, 'taskrouter_target_taskqueue_sid'),
            'taskrouterTaskqueues' => Values::array_get($payload, 'taskrouter_taskqueues'),
            'taskrouterSkills' => Values::array_get($payload, 'taskrouter_skills'),
            'taskrouterWorkerChannels' => Values::array_get($payload, 'taskrouter_worker_channels'),
            'taskrouterWorkerAttributes' => Values::array_get($payload, 'taskrouter_worker_attributes'),
            'taskrouterOfflineActivitySid' => Values::array_get($payload, 'taskrouter_offline_activity_sid'),
            'runtimeDomain' => Values::array_get($payload, 'runtime_domain'),
            'messagingServiceInstanceSid' => Values::array_get($payload, 'messaging_service_instance_sid'),
            'chatServiceInstanceSid' => Values::array_get($payload, 'chat_service_instance_sid'),
            'uiLanguage' => Values::array_get($payload, 'ui_language'),
            'uiAttributes' => Values::array_get($payload, 'ui_attributes'),
            'uiVersion' => Values::array_get($payload, 'ui_version'),
            'serviceVersion' => Values::array_get($payload, 'service_version'),
            'callRecordingEnabled' => Values::array_get($payload, 'call_recording_enabled'),
            'callRecordingWebhookUrl' => Values::array_get($payload, 'call_recording_webhook_url'),
            'crmEnabled' => Values::array_get($payload, 'crm_enabled'),
            'crmType' => Values::array_get($payload, 'crm_type'),
            'crmCallbackUrl' => Values::array_get($payload, 'crm_callback_url'),
            'crmFallbackUrl' => Values::array_get($payload, 'crm_fallback_url'),
            'crmAttributes' => Values::array_get($payload, 'crm_attributes'),
            'publicAttributes' => Values::array_get($payload, 'public_attributes'),
            'pluginServiceEnabled' => Values::array_get($payload, 'plugin_service_enabled'),
            'pluginServiceAttributes' => Values::array_get($payload, 'plugin_service_attributes'),
            'integrations' => Values::array_get($payload, 'integrations'),
            'outboundCallFlows' => Values::array_get($payload, 'outbound_call_flows'),
            'featuresEnabled' => Values::array_get($payload, 'features_enabled'),
            'serverlessServiceSids' => Values::array_get($payload, 'serverless_service_sids'),
            'url' => Values::array_get($payload, 'url'),
        );

        $this->solution = array();
    }

    /**
     * Generate an instance context for the instance, the context is capable of
     * performing various actions.  All instance actions are proxied to the context
     *
     * @return \Twilio\Rest\FlexApi\V1\ConfigurationContext Context for this
     *                                                      ConfigurationInstance
     */
    protected function proxy() {
        if (!$this->context) {
            $this->context = new ConfigurationContext($this->version);
        }

        return $this->context;
    }

    /**
     * Fetch a ConfigurationInstance
     *
     * @param array|Options $options Optional Arguments
     * @return ConfigurationInstance Fetched ConfigurationInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch($options = array()) {
        return $this->proxy()->fetch($options);
    }

    /**
     * Create a new ConfigurationInstance
     *
     * @return ConfigurationInstance Newly created ConfigurationInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function create() {
        return $this->proxy()->create();
    }

    /**
     * Update the ConfigurationInstance
     *
     * @return ConfigurationInstance Updated ConfigurationInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function update() {
        return $this->proxy()->update();
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
        return '[Twilio.FlexApi.V1.ConfigurationInstance ' . implode(' ', $context) . ']';
    }
}