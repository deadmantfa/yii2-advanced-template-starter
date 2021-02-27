<?php


namespace common\components;

class ArrayHelper extends \yii\helpers\ArrayHelper
{


// Function to iteratively search for a given value
    public static function searchForId($search_value, $array, $id_path)
    {
        if (is_array($array) && count($array) > 0) {
            foreach ($array as $key => $value) {
                $temp_path = $id_path;

                // Adding current key to search path
                $temp_path[] = (is_string($key)) ? '"' . $key . '"' : $key;

                // Check if this value is an array
                // with at least one element
                if (is_array($value) && count($value) > 0) {
                    $res_path = self::searchForId(
                        $search_value,
                        $value,
                        $temp_path
                    );

                    if ($res_path !== null) {
                        return $res_path;
                    }
                } elseif ($value === $search_value) {
                    return '[' . implode("][", $temp_path) . ']';
                }
            }
        }

        return null;
    }

    public static function searchValues(array $search, array $array)
    {
        $count = count($search);
        foreach ($array as $index => $data) {
            $test = 0;
            foreach ($search as $searchKey => $value) {
                if ($data[$searchKey] === $value) {
                    ++$test;
                }
            }
            if ($test === $count) {
                return $index;
            }
        }
        return null;
    }
}
