<?php
/**
 * SegmentValuesRequestDTOResourceObjectAttributes
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
 * SegmentValuesRequestDTOResourceObjectAttributes Class Doc Comment
 *
 * @category Class
 * @package  KlaviyoAPI
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class SegmentValuesRequestDTOResourceObjectAttributes implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'SegmentValuesRequestDTOResourceObject_attributes';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'statistics' => 'string[]',
        'timeframe' => '\KlaviyoAPI\Model\SegmentValuesRequestDTOResourceObjectAttributesTimeframe',
        'filter' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'statistics' => null,
        'timeframe' => null,
        'filter' => null
    ];

    /**
      * Array of nullable properties. Used for (de)serialization
      *
      * @var boolean[]
      */
    protected static array $openAPINullables = [
        'statistics' => false,
		'timeframe' => false,
		'filter' => true
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
        'statistics' => 'statistics',
        'timeframe' => 'timeframe',
        'filter' => 'filter'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'statistics' => 'setStatistics',
        'timeframe' => 'setTimeframe',
        'filter' => 'setFilter'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'statistics' => 'getStatistics',
        'timeframe' => 'getTimeframe',
        'filter' => 'getFilter'
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

    public const STATISTICS_MEMBERS_ADDED = 'members_added';
    public const STATISTICS_MEMBERS_REMOVED = 'members_removed';
    public const STATISTICS_NET_MEMBERS_CHANGED = 'net_members_changed';
    public const STATISTICS_TOTAL_MEMBERS = 'total_members';

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getStatisticsAllowableValues()
    {
        return [
            self::STATISTICS_MEMBERS_ADDED,
            self::STATISTICS_MEMBERS_REMOVED,
            self::STATISTICS_NET_MEMBERS_CHANGED,
            self::STATISTICS_TOTAL_MEMBERS,
        ];
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
        $this->setIfExists('statistics', $data ?? [], null);
        $this->setIfExists('timeframe', $data ?? [], null);
        $this->setIfExists('filter', $data ?? [], null);
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

        if ($this->container['statistics'] === null) {
            $invalidProperties[] = "'statistics' can't be null";
        }
        if ($this->container['timeframe'] === null) {
            $invalidProperties[] = "'timeframe' can't be null";
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
     * Gets statistics
     *
     * @return string[]
     */
    public function getStatistics()
    {
        return $this->container['statistics'];
    }

    /**
     * Sets statistics
     *
     * @param string[] $statistics List of statistics to query for.
     *
     * @return self
     */
    public function setStatistics($statistics)
    {
        $allowedValues = $this->getStatisticsAllowableValues();
        if (array_diff($statistics, $allowedValues)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value for 'statistics', must be one of '%s'",
                    implode("', '", $allowedValues)
                )
            );
        }

        if (is_null($statistics)) {
            throw new \InvalidArgumentException('non-nullable statistics cannot be null');
        }

        $this->container['statistics'] = $statistics;

        return $this;
    }

    /**
     * Gets timeframe
     *
     * @return \KlaviyoAPI\Model\SegmentValuesRequestDTOResourceObjectAttributesTimeframe
     */
    public function getTimeframe()
    {
        return $this->container['timeframe'];
    }

    /**
     * Sets timeframe
     *
     * @param \KlaviyoAPI\Model\SegmentValuesRequestDTOResourceObjectAttributesTimeframe $timeframe timeframe
     *
     * @return self
     */
    public function setTimeframe($timeframe)
    {

        if (is_null($timeframe)) {
            throw new \InvalidArgumentException('non-nullable timeframe cannot be null');
        }

        $this->container['timeframe'] = $timeframe;

        return $this;
    }

    /**
     * Gets filter
     *
     * @return string|null
     */
    public function getFilter()
    {
        return $this->container['filter'];
    }

    /**
     * Sets filter
     *
     * @param string|null $filter API filter string used to filter the query. Allowed filters are segment_id. Allowed operators are equals, any. Only one filter can be used per attribute. Max of 100 messages per ANY filter.
     *
     * @return self
     */
    public function setFilter($filter)
    {

        if (is_null($filter)) {
            array_push($this->openAPINullablesSetToNull, 'filter');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('filter', $nullablesSetToNull);
            if ($index !== FALSE) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }

        $this->container['filter'] = $filter;

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


