<?php

use PHPUnit\Framework\TestCase;

class Invoker extends TestCase {

    public function invokeMethod(&$object, $methodName, array $parameters = array()) {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /**
     * Sets a private property on a given object via reflection
     *
     * @param Object $object    Object in which private value is being modified
     * @param string $property  Property on instance being modified
     * @param mixed  $value     New value of the property being modified
     *
     * @return void
     */
    public function setPrivateProperty(&$object, $property, $value) {
        $reflection = new \ReflectionClass($object);
        $reflection_property = $reflection->getProperty($property);
        $reflection_property->setAccessible(true);
        $reflection_property->setValue($object, $value);
    }

    /**
     * Gets a private property on a given object via reflection
     *
     * @param  Object $object    The object of the class containing the property
     * @param  string $property  The name of the property to obtain the value for
     * @return mixed             The value of the property
     */
    public function getPrivateProperty($object, $property) {
        $reflection = new \ReflectionClass($object);
        $reflection_property = $reflection->getProperty($property);
        $reflection_property->setAccessible(true);
        return $reflection_property->getValue($object);
    }

    /**
     * Sets a protected property on a given object via reflection
     *
     * @param object $object - instance in which protected value is being modified
     * @param string $property - property on instance being modified
     * @param mixed $value - new value of the property being modified
     *
     * @return void
     */
    public function setProtectedProperty(&$object, $property, $value) {
        $this->setPrivateProperty($object, $property, $value);
    }

    // public function getProtectedProperty($object, $property) {
    //     $reflection = new \ReflectionClass($object);
    //     $reflection_property = $reflection->getProperty($property);
    //     $reflection_property->setAccessible(true);
    //     $reflection_property->getValue($object);
    // }   
}
