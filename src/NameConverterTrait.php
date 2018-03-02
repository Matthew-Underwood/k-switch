<?php

namespace Lexide\KSwitch;

trait NameConverterTrait 
{
    /**
     * @param string $string
     * @return string
     */
    private function toStudlyCaps($string)
    {
        return str_replace( // remove the spaces
            " ",
            "",
            ucwords( // uppercase the 1st letter of each word
                preg_replace( // replace non-alphanumeric characters with spaces
                    "/[^A-Za-z0-9]/",
                    " ",
                    $string
                )
            )
        );
    }

    private function toCamelCase($string)
    {
        return lcfirst($this->toStudlyCaps($string));
    }

    /**
     * @param string $string
     * @param string $separator
     * @return string
     */
    private function toSplitCase($string, $separator = "_")
    {
        return strtolower(
            preg_replace( // precede any capital letters or numbers with the separator (except when the character starts the string)
                "/(?<!^|_)([A-Z]|\\d+)/",
                $separator . '$1',
                preg_replace( // replace any non-word characters with the separator (e.g. for converting dash case to snake case)
                    "/[^A-Za-z0-9]/",
                    $separator,
                    $string
                )
            )
        );
    }

    /**
     * convert the keys of an array
     *
     * @param array $data
     * @param $case
     * @return array
     */
    private function convertArrayKeys(array $data, $case) {

        foreach ($data as $property => $value) {
            $originalProperty = $property;
            switch ($case) {
                case StringCases::STUDLY_CAPS:
                    $property = $this->toStudlyCaps($property);
                    break;
                case StringCases::CAMEL_CASE:
                    $property = $this->toCamelCase($property);
                    break;
                case StringCases::SNAKE_CASE:
                    $property = $this->toSplitCase($property);
                    break;
                case StringCases::DASH_CASE:
                    $property = $this->toSplitCase($property, "-");
                    break;
                default:
                    if (strlen($case) == 1) {
                        $property = $this->toSplitCase($property, $case);
                    }
                    break;
            }
            if ($property != $originalProperty) {
                unset($data[$originalProperty]);
                $data[$property] = $value;
            }
        }

        return $data;
    }
} 
