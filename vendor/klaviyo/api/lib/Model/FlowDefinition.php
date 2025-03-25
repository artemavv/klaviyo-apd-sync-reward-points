<?php
/**
 * FlowDefinition
 *
 * PHP version 7.4
 *
 * @category Class
 * @package  KlaviyoAPI
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */

/**
 * Klaviyo API
 *
 * The Klaviyo REST API. Please visit https://developers.klaviyo.com for more details.
 *
 * The version of the OpenAPI document: 2025-01-15
 * Contact: developers@klaviyo.com
 * Generated by: https://openapi-generator.tech
 * OpenAPI Generator version: 6.1.0
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace KlaviyoAPI\Model;

use \ArrayAccess;
use \KlaviyoAPI\ObjectSerializer;

/**
 * FlowDefinition Class Doc Comment
 *
 * @category Class
 * @package  KlaviyoAPI
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class FlowDefinition implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'FlowDefinition';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'triggers' => '\KlaviyoAPI\Model\FlowDefinitionTriggersInner[]',
        'profile_filter' => '\KlaviyoAPI\Model\UnionFilter',
        'actions' => '\KlaviyoAPI\Model\FlowDefinitionActionsInner[]',
        'entry_action_id' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'triggers' => null,
        'profile_filter' => null,
        'actions' => null,
        'entry_action_id' => null
    ];

    /**
      * Array of nullable properties. Used for (de)serialization
      *
      * @var boolean[]
      */
    protected static array $openAPINullables = [
        'triggers' => false,
		'profile_filter' => false,
		'actions' => false,
		'entry_action_id' => true
    ];

    /**
      * If a nullable field gets set to null, insert it here
      *
      * @var boolean[]
      */
    protected array $openAPINullablesSetToNull = [];

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function openAPITypes()
    {
        return self::$openAPITypes;
    }

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function openAPIFormats()
    {
        return self::$openAPIFormats;
    }

    /**
     * Array of nullable properties
     *
     * @return array
     */
    protected static function openAPINullables(): array
    {
        return self::$openAPINullables;
    }

    /**
     * Array of nullable field names deliberately set to null
     *
     * @return boolean[]
     */
    private function getOpenAPINullablesSetToNull(): array
    {
        return $this->openAPINullablesSetToNull;
    }

    /**
     * Checks if a property is nullable
     *
     * @param string $property
     * @return bool
     */
    public static function isNullable(string $property): bool
    {
        return self::openAPINullables()[$property] ?? false;
    }

    /**
     * Checks if a nullable property is set to null.
     *
     * @param string $property
     * @return bool
     */
    public function isNullableSetToNull(string $property): bool
    {
        return in_array($property, $this->getOpenAPINullablesSetToNull(), true);
    }

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'triggers' => 'triggers',
        'profile_filter' => 'profile_filter',
        'actions' => 'actions',
        'entry_action_id' => 'entry_action_id'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'triggers' => 'setTriggers',
        'profile_filter' => 'setProfileFilter',
        'actions' => 'setActions',
        'entry_action_id' => 'setEntryActionId'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'triggers' => 'getTriggers',
        'profile_filter' => 'getProfileFilter',
        'actions' => 'getActions',
        'entry_action_id' => 'getEntryActionId'
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @return array
     */
    public static function attributeMap()
    {
        return self::$attributeMap;
    }

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @return array
     */
    public static function setters()
    {
        return self::$setters;
    }

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @return array
     */
    public static function getters()
    {
        return self::$getters;
    }

    /**
     * The original name of the model.
     *
     * @return string
     */
    public function getModelName()
    {
        return self::$openAPIModelName;
    }


    /**
     * Associative array for storing property values
     *
     * @var mixed[]
     */
    protected $container = [];

    /**
     * Constructor
     *
     * @param mixed[] $data Associated array of property values
     *                      initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->setIfExists('triggers', $data ?? [], null);
        $this->setIfExists('profile_filter', $data ?? [], null);
        $this->setIfExists('actions', $data ?? [], null);
        $this->setIfExists('entry_action_id', $data ?? [], null);
    }

    /**
    * Sets $this->container[$variableName] to the given data or to the given default Value; if $variableName
    * is nullable and its value is set to null in the $fields array, then mark it as "set to null" in the
    * $this->openAPINullablesSetToNull array
    *
    * @param string $variableName
    * @param array  $fields
    * @param mixed  $defaultValue
    */
    private function setIfExists(string $variableName, array $fields, $defaultValue): void
    {
        if (self::isNullable($variableName) && array_key_exists($variableName, $fields) && is_null($fields[$variableName])) {
            $this->openAPINullablesSetToNull[] = $variableName;
        }

        $this->container[$variableName] = $fields[$variableName] ?? $defaultValue;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        if ($this->container['triggers'] === null) {
            $invalidProperties[] = "'triggers' can't be null";
        }
        if ($this->container['profile_filter'] === null) {
            $invalidProperties[] = "'profile_filter' can't be null";
        }
        if ($this->container['actions'] === null) {
            $invalidProperties[] = "'actions' can't be null";
        }
        if ($this->container['entry_action_id'] === null) {
            $invalidProperties[] = "'entry_action_id' can't be null";
        }
        return $invalidProperties;
    }

    /**
     * Validate all the properties in the model
     * return true if all passed
     *
     * @return bool True if all properties are valid
     */
    public function valid()
    {
        return count($this->listInvalidProperties()) === 0;
    }


    /**
     * Gets triggers
     *
     * @return \KlaviyoAPI\Model\FlowDefinitionTriggersInner[]
     */
    public function getTriggers()
    {
        return $this->container['triggers'];
    }

    /**
     * Sets triggers
     *
     * @param \KlaviyoAPI\Model\FlowDefinitionTriggersInner[] $triggers Corresponds to the object which triggers the flow. Only one trigger is supported.
     *
     * @return self
     */
    public function setTriggers($triggers)
    {

        if (is_null($triggers)) {
            throw new \InvalidArgumentException('non-nullable triggers cannot be null');
        }

        $this->container['triggers'] = $triggers;

        return $this;
    }

    /**
     * Gets profile_filter
     *
     * @return \KlaviyoAPI\Model\UnionFilter
     */
    public function getProfileFilter()
    {
        return $this->container['profile_filter'];
    }

    /**
     * Sets profile_filter
     *
     * @param \KlaviyoAPI\Model\UnionFilter $profile_filter profile_filter
     *
     * @return self
     */
    public function setProfileFilter($profile_filter)
    {

        if (is_null($profile_filter)) {
            throw new \InvalidArgumentException('non-nullable profile_filter cannot be null');
        }

        $this->container['profile_filter'] = $profile_filter;

        return $this;
    }

    /**
     * Gets actions
     *
     * @return \KlaviyoAPI\Model\FlowDefinitionActionsInner[]
     */
    public function getActions()
    {
        return $this->container['actions'];
    }

    /**
     * Sets actions
     *
     * @param \KlaviyoAPI\Model\FlowDefinitionActionsInner[] $actions A list of actions that make up the flow. Actions are linked to each other by their ids.
     *
     * @return self
     */
    public function setActions($actions)
    {

        if (is_null($actions)) {
            throw new \InvalidArgumentException('non-nullable actions cannot be null');
        }

        $this->container['actions'] = $actions;

        return $this;
    }

    /**
     * Gets entry_action_id
     *
     * @return string
     */
    public function getEntryActionId()
    {
        return $this->container['entry_action_id'];
    }

    /**
     * Sets entry_action_id
     *
     * @param string $entry_action_id The ID of the action that is the entry point of the flow.
     *
     * @return self
     */
    public function setEntryActionId($entry_action_id)
    {

        if (is_null($entry_action_id)) {
            array_push($this->openAPINullablesSetToNull, 'entry_action_id');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('entry_action_id', $nullablesSetToNull);
            if ($index !== FALSE) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }

        $this->container['entry_action_id'] = $entry_action_id;

        return $this;
    }
    /**
     * Returns true if offset exists. False otherwise.
     *
     * @param integer $offset Offset
     *
     * @return boolean
     */
    public function offsetExists($offset): bool
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     *
     * @param integer $offset Offset
     *
     * @return mixed|null
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->container[$offset] ?? null;
    }

    /**
     * Sets value based on offset.
     *
     * @param int|null $offset Offset
     * @param mixed    $value  Value to be set
     *
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * Unsets offset.
     *
     * @param integer $offset Offset
     *
     * @return void
     */
    public function offsetUnset($offset): void
    {
        unset($this->container[$offset]);
    }

    /**
     * Serializes the object to a value that can be serialized natively by json_encode().
     * @link https://www.php.net/manual/en/jsonserializable.jsonserialize.php
     *
     * @return mixed Returns data which can be serialized by json_encode(), which is a value
     * of any type other than a resource.
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
       return ObjectSerializer::sanitizeForSerialization($this);
    }

    /**
     * Gets the string presentation of the object
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode(
            ObjectSerializer::sanitizeForSerialization($this),
            JSON_PRETTY_PRINT
        );
    }

    /**
     * Gets a header-safe presentation of the object
     *
     * @return string
     */
    public function toHeaderValue()
    {
        return json_encode(ObjectSerializer::sanitizeForSerialization($this));
    }
}


