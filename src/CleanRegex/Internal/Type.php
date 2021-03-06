<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use function count;
use function get_class;
use function gettype;
use function is_array;
use function is_resource;
use function is_scalar;

class Type
{
    public static function asString($value): string
    {
        if ($value === null) {
            return 'null';
        }
        if (is_scalar($value)) {
            $type = gettype($value);
            $value = \var_export($value, true);
            return "$type ($value)";
        }
        if (is_array($value)) {
            $count = count($value);
            return "array ($count)";
        }
        if (is_resource($value)) {
            return 'resource';
        }
        return get_class($value);
    }

    public static function group($nameOrIndex): string
    {
        if (\is_string($nameOrIndex)) {
            return "'$nameOrIndex'";
        }
        if (\is_int($nameOrIndex)) {
            return "#$nameOrIndex";
        }
        // @codeCoverageIgnoreStart
        throw new InternalCleanRegexException();
        // @codeCoverageIgnoreEnd
    }

    public static function entry($key, $value): string
    {
        $type = Type::asString($value);
        $formatKey = Type::group($key);
        return "[$formatKey => $type]";
    }
}
