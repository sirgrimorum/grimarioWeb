<?php

function getMensajesValidacion() {
    $mensajes = [
        'required' => 'The :attribute field is required.',
    ];
    return $mensajes;
}

/**
 * Get the value of a property using reflection.
 *
 * @param object|string $class
 *     The object or classname to reflect. An object must be provided
 *     if accessing a non-static property.
 * @param string $propertyName The property to reflect.
 * @return mixed The value of the reflected property.
 */
function getReflectedPropertyValue($class, $propertyName) {
    $reflectedClass = new ReflectionClass($class);
    $property = $reflectedClass->getProperty($propertyName);
    $property->setAccessible(true);

    return $property->getValue($class);
}

