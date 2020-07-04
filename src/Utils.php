<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

use Error;
use Exception;
use Murtukov\PHPCodeGenerator\Exception\UnrecognizedValueTypeException;
use function get_class;
use function is_int;
use function json_encode;
use function rtrim;
use function str_replace;
use function substr;
use function var_export;

class Utils
{
    const TYPE_STRING = 'string';
    const TYPE_INT = 'integer';
    const TYPE_BOOL = 'boolean';
    const TYPE_DOUBLE = 'double';
    const TYPE_OBJECT = 'object';
    const TYPE_ARRAY = 'array';

    /**
     * @var bool whether arrays should be split into multiple lines
     */
    private static ?bool $multiline = false;

    /**
     * @var bool defines whether arrays should be rendered with keys
     */
    private static ?bool $withKeys = false;

    /**
     * @var bool if true, null values are not rendered
     */
    public static bool $skipNullValues = false;

    /**
     * @var array custom converters registered by users
     */
    private static array $customConverters = [];

    /**
     * @param mixed $value
     *
     * @throws UnrecognizedValueTypeException
     */
    public static function stringify($value, ?bool $multiline = null, ?bool $withKeys = null, array $converters = []): string
    {
        // Common options to avoid passing them recursively
        self::$multiline = $multiline;
        self::$withKeys = $withKeys;
        self::$customConverters = $converters;

        return self::stringifyValue($value, true);
    }

    /**
     * @param mixed $value
     *
     * @throws UnrecognizedValueTypeException
     * @throws Exception
     */
    private static function stringifyValue($value, bool $topLevel = false): string
    {
        $type = gettype($value);

        // Custom converters
        if (!empty(self::$customConverters)) {
            foreach (Config::getConverterClasses($type) as $fqcn) {
                $converter = Config::getConverter($fqcn);
                if ($converter && $converter->check($value)) {
                    return (string) $converter->convert($value);
                }
            }
        }

        // Default converters
        switch ($type) {
            case 'boolean':
            case 'integer':
            case 'double':
                return json_encode($value);
            case 'string':
                if ('' === $value) {
                    return "''";
                }

                return var_export($value, true);
            case 'array':
                if (empty($value)) {
                    return '[]';
                }

                if (null !== self::$withKeys && true === $topLevel) {
                    return self::$withKeys
                        ? self::stringifyAssocArray($value, self::$multiline)
                        : self::stringifyNumericArray($value, self::$multiline);
                }

                return isset($value[0])
                    ? self::stringifyNumericArray($value)
                    : self::stringifyAssocArray($value);

            case 'object':
                if (!$value instanceof GeneratorInterface) {
                    try {
                        $result = json_encode($value->__toString());

                        return false !== $result ? $result : '[object]';
                    } catch (Error $e) {
                        $class = get_class($value);
                        throw new Exception("Cannot stringify object of class: '$class'.");
                    }
                }

                return (string) $value;
            case 'NULL':
                if (self::$skipNullValues) {
                    return '';
                }

                return 'null';
            default:
                throw new UnrecognizedValueTypeException('Cannot stringify value of unrecognized type.');
        }
    }

    /**
     * @throws UnrecognizedValueTypeException
     */
    private static function stringifyAssocArray(array $items, ?bool $multiline = true): string
    {
        $code = '';

        if ($multiline) {
            $code .= "\n";

            foreach ($items as $key => $value) {
                $key = is_int($key) ? $key : "'$key'";
                $value = self::stringifyValue($value);
                $code .= "$key => $value,\n";
            }

            $code = Utils::indent($code, false);
        } else {
            foreach ($items as $key => $value) {
                $key = is_int($key) ? $key : "'$key'";
                $value = self::stringifyValue($value);
                $code .= "$key => $value, ";
            }
        }

        // Remove last comma
        $code = rtrim($code, ', ');

        return "[$code]";
    }

    /**
     * @throws UnrecognizedValueTypeException
     */
    private static function stringifyNumericArray(array $items, ?bool $multiline = false): string
    {
        $code = '';

        if ($multiline) {
            $code .= "\n";

            foreach ($items as $value) {
                $value = self::stringifyValue($value);
                $code .= "$value,\n";
            }

            $code = Utils::indent($code, false);
        } else {
            foreach ($items as $value) {
                $value = self::stringifyValue($value);
                $code .= "$value, ";
            }
        }

        // Remove last comma and space
        $code = rtrim($code, ', ');

        return "[$code]";
    }

    public static function indent(string $code, bool $leadingIndent = true): string
    {
        $indent = Config::$indent;
        $code = str_replace("\n", "\n$indent", $code);

        if (true === $leadingIndent) {
            $code = $indent.$code;
        }

        return $code;
    }

    public static function resolveQualifier(string $path)
    {
        if ($portion = strrchr($path, '\\')) {
            return substr($portion, 1);
        }

        return false;
    }
}
