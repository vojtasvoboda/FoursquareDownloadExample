<?php

/**
 * Return multidimensional array/object as two dimensional array
 *
 * @param $data
 * @param $allowFields
 *
 * @return array
 */
function get_venue_data_flatten($data, $allowFields)
{
    // if data is object, convert them
    if (is_object($data)) {
        $data = object_to_array($data);
    }
    // fix data (we save place ID as place_id)
    $data['place_id'] = $data['id'];
    unset($data['id']);
    // convert array to json value
    if (isset($data['specials']['items'])) {
        $data['specials']['items'] = substr(json_encode($data['specials']['items']), 0, 255);
    }
    // return flatten array
    return get_array_from_object_flatten($data, $allowFields);
}

/**
 * Return two dimensional array from multidimensional object
 *
 * @param $data
 * @param $allowFields
 *
 * @return array
 */
function get_array_from_object_flatten($data, $allowFields, $prefix = '')
{
    $out = array();
    if ( !empty($prefix) ) {
        $prefix = $prefix . '_';
    }
    foreach ($data as $key => $child) {
        if (is_array($child)) {
            $out = array_merge($out, get_array_from_object_flatten($child, $allowFields, $prefix . $key));
        } else {
            if ( in_array($prefix . $key, $allowFields) ) {
                $out[$prefix . $key] = $child;
            }
        }
    }
    return $out;
}

/**
 * Convert object to array, recursively
 *
 * @param $object
 *
 * @return array
 */
function object_to_array($object)
{
    if (is_object($object)) {
        // Gets the properties of the given object
        // with get_object_vars function
        $object = get_object_vars($object);
    }
    if (is_array($object)) {
        /*
         * Return array converted to object
         * for recursive call
         */
        return array_map('object_to_array', $object);
    } else {
        // Return array
        return $object;
    }
}
