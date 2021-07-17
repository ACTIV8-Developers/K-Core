<?php
namespace Core\Http;

/**
 * Simple container class for storing HTTP request data.
 */
class HttpBag implements \IteratorAggregate, \Countable, \ArrayAccess
{
    /**
     * Elements storage.
     *
     * @var array
     */
    protected array $elements = [];

    /**
     * Constructor.
     *
     * @param array $elements
     */
    public function __construct(array $elements = [])
    {
        $this->elements = $elements;
    }

    /**
     * Returns the elements.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->elements;
    }

    /**
     * Returns the elements keys.
     *
     * @return array
     */
    public function keys(): array
    {
        return array_keys($this->elements);
    }

    /**
     * Returns the elements values.
     *
     * @return array
     */
    public function values(): array
    {
        return array_values($this->elements);
    }

    /**
     * Replaces the current elements by a new set.
     *
     * @param array $elements
     */
    public function replace(array $elements = [])
    {
        $this->elements = $elements;
    }

    /**
     * Adds elements.
     *
     * @param array $elements
     */
    public function add(array $elements = [])
    {
        $this->elements = array_merge($this->elements, $elements);
    }

    /**
     * Sets a element by name.
     *
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        $this->elements[$key] = $value;
    }

    /**
     * Returns true if the element is defined.
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->elements);
    }

    /**
     * Removes a element.
     *
     * @param string $key
     */
    public function remove(string $key)
    {
        unset($this->elements[$key]);
    }

    /**
     * Removes all elements.
     */
    public function clear()
    {
        unset($this->elements);
        $this->elements = [];
    }

    /**
     * Filter key.
     *
     * @param string $key
     * @param int $filter FILTER_* constant.
     * @param mixed $options .
     * @see http://php.net/manual/en/function.filter-var.php
     * @return mixed
     */
    public function filter($key, $filter = FILTER_DEFAULT, $options = [])
    {
        $value = $this->get($key);

        // Always turn $options into an array - this allows filter_var option shortcuts.
        if (!is_array($options) && $options) {
            $options = array('flags' => $options);
        }

        // Add a convenience check for arrays.
        if (is_array($value) && !isset($options['flags'])) {
            $options['flags'] = FILTER_REQUIRE_ARRAY;
        }

        return filter_var($value, $filter, $options);
    }

    /**
     * Returns a element by name.
     *
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
    {
        return $this->elements[$key] ?? null;
    }

    /**
     * Returns an iterator for elements.
     *
     * @return \ArrayIterator
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->elements);
    }

    /**
     * Returns the number of elements.
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->elements);
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset): bool
    {
        return isset($this->elements[$offset]);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        if (isset($this->elements[$offset])) {
           return $this->elements[$offset];
        }
        return null;
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        $this->elements[$offset] = $value;
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        unset($this->elements[$offset]);
    }
}
