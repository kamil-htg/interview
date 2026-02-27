<?php

declare(strict_types=1);

namespace App\Common\Core\Assert;

use App\Common\Core\Exception\AssertFailedException;
use Countable;
use XMLReader;

/**
 * @throws AssertFailedException
 * @method static void string(mixed $value, string $message = '')
 * @method static void stringNotEmpty(mixed $value, string $message = '')
 * @method static void integer(mixed $value, string $message = '')
 * @method static void integerish(mixed $value, string $message = '')
 * @method static void positiveInteger(mixed $value, string $message = '')
 * @method static void float(mixed $value, string $message = '')
 * @method static void numeric(mixed $value, string $message = '')
 * @method static void natural(mixed $value, string $message = '')
 * @method static void boolean(mixed $value, string $message = '')
 * @method static void scalar(mixed $value, string $message = '')
 * @method static void object(mixed $value, string $message = '')
 * @method static void resource(mixed $value, ?string $type = null, string $message = '')
 * @method static void isCallable(mixed $value, string $message = '')
 * @method static void isArray(mixed $value, string $message = '')
 * @method static void isTraversable(mixed $value, string $message = '')
 * @method static void isArrayAccessible(mixed $value, string $message = '')
 * @method static void isCountable(mixed $value, string $message = '')
 * @method static void isIterable(mixed $value, string $message = '')
 * @method static void isInstanceOf(mixed $value, object|string $class, string $message = '')
 * @method static void notInstanceOf(mixed $value, object|string $class, string $message = '')
 * @method static void isInstanceOfAny(mixed $value, mixed[] $classes, string $message = '')
 * @method static void isAOf(object|string $value, string $class, string $message = '')
 * @method static void isNotA(object|string $value, string $class, string $message = '')
 * @method static void isAnyOf(object|string $value, string[] $classes, string $message = '')
 * @method static void isEmpty(mixed $value, string $message = '')
 * @method static void notEmpty(mixed $value, string $message = '')
 * @method static void null(mixed $value, string $message = '')
 * @method static void notNull(mixed $value, string $message = '')
 * @method static void true(mixed $value, string $message = '')
 * @method static void false(mixed $value, string $message = '')
 * @method static void notFalse(mixed $value, string $message = '')
 * @method static void ip(mixed $value, string $message = '')
 * @method static void ipv4(mixed $value, string $message = '')
 * @method static void ipv6(mixed $value, string $message = '')
 * @method static void email(mixed $value, string $message = '')
 * @method static void uniqueValues(mixed[] $values, string $message = '')
 * @method static void eq(mixed $value, mixed $expect, string $message = '')
 * @method static void notEq(mixed $value, mixed $expect, string $message = '')
 * @method static void same(mixed $value, mixed $expect, string $message = '')
 * @method static void notSame(mixed $value, mixed $expect, string $message = '')
 * @method static void greaterThan(mixed $value, mixed $limit, string $message = '')
 * @method static void greaterThanEq(mixed $value, mixed $limit, string $message = '')
 * @method static void lessThan(mixed $value, mixed $limit, string $message = '')
 * @method static void lessThanEq(mixed $value, mixed $limit, string $message = '')
 * @method static void range(mixed $value, mixed $min, mixed $max, string $message = '')
 * @method static void oneOf(mixed $value, mixed[] $values, string $message = '')
 * @method static void inArray(mixed $value, mixed[] $values, string $message = '')
 * @method static void contains(string $value, string $subString, string $message = '')
 * @method static void notContains(string $value, string $subString, string $message = '')
 * @method static void notWhitespaceOnly(string $value, string $message = '')
 * @method static void startsWith(string $value, string $prefix, string $message = '')
 * @method static void notStartsWith(string $value, string $prefix, string $message = '')
 * @method static void startsWithLetter(mixed $value, string $message = '')
 * @method static void endsWith(string $value, string $suffix, string $message = '')
 * @method static void notEndsWith(string $value, string $suffix, string $message = '')
 * @method static void regex(string $value, string $pattern, string $message = '')
 * @method static void notRegex(string $value, string $pattern, string $message = '')
 * @method static void unicodeLetters(mixed $value, string $message = '')
 * @method static void alpha(mixed $value, string $message = '')
 * @method static void digits(string $value, string $message = '')
 * @method static void alnum(string $value, string $message = '')
 * @method static void lower(string $value, string $message = '')
 * @method static void upper(string $value, string $message = '')
 * @method static void length(string $value, int $length, string $message = '')
 * @method static void minLength(string $value, int|float $min, string $message = '')
 * @method static void maxLength(string $value, int|float $max, string $message = '')
 * @method static void lengthBetween(string $value, int|float $min, int|float $max, string $message = '')
 * @method static void fileExists(mixed $value, string $message = '')
 * @method static void file(mixed $value, string $message = '')
 * @method static void directory(mixed $value, string $message = '')
 * @method static void readable(string $value, string $message = '')
 * @method static void writable(string $value, string $message = '')
 * @method static void classExists(mixed $value, string $message = '')
 * @method static void subclassOf(mixed $value, object|string $class, string $message = '')
 * @method static void interfaceExists(mixed $value, string $message = '')
 * @method static void implementsInterface(mixed $value, mixed $interface, string $message = '')
 * @method static void propertyExists(object|string $classOrObject, mixed $property, string $message = '')
 * @method static void propertyNotExists(object|string $classOrObject, mixed $property, string $message = '')
 * @method static void methodExists(object|string $classOrObject, mixed $method, string $message = '')
 * @method static void methodNotExists(object|string $classOrObject, mixed $method, string $message = '')
 * @method static void keyExists(mixed[] $array, string|int $key, string $message = '')
 * @method static void keyNotExists(mixed[] $array, string|int $key, string $message = '')
 * @method static void validArrayKey(mixed $value, string $message = '')
 * @method static void count(Countable|mixed[] $array, int $number, string $message = '')
 * @method static void minCount(Countable|mixed[] $array, int|float $min, string $message = '')
 * @method static void maxCount(Countable|mixed[] $array, int|float $max, string $message = '')
 * @method static void countBetween(Countable|mixed[] $array, int|float $min, int|float $max, string $message = '')
 * @method static void isList(mixed $array, string $message = '')
 * @method static void isNonEmptyList(mixed $array, string $message = '')
 * @method static void isMap(mixed $array, string $message = '')
 * @method static void isNonEmptyMap(mixed[] $array, string $message = '')
 * @method static void uuid(string $value, string $message = '')
 * @method static void throws(\Closure $expression, string $class = 'Exception', string $message = '')
 * @method static void nullOrString($value, $message = '')
 * @method static void allString($value, $message = '')
 * @method static void allNullOrString($value, $message = '')
 * @method static void nullOrStringNotEmpty($value, $message = '')
 * @method static void allStringNotEmpty($value, $message = '')
 * @method static void allNullOrStringNotEmpty($value, $message = '')
 * @method static void nullOrInteger($value, $message = '')
 * @method static void allInteger($value, $message = '')
 * @method static void allNullOrInteger($value, $message = '')
 * @method static void nullOrIntegerish($value, $message = '')
 * @method static void allIntegerish($value, $message = '')
 * @method static void allNullOrIntegerish($value, $message = '')
 * @method static void nullOrPositiveInteger($value, $message = '')
 * @method static void allPositiveInteger($value, $message = '')
 * @method static void allNullOrPositiveInteger($value, $message = '')
 * @method static void nullOrFloat($value, $message = '')
 * @method static void allFloat($value, $message = '')
 * @method static void allNullOrFloat($value, $message = '')
 * @method static void nullOrNumeric($value, $message = '')
 * @method static void allNumeric($value, $message = '')
 * @method static void allNullOrNumeric($value, $message = '')
 * @method static void nullOrNatural($value, $message = '')
 * @method static void allNatural($value, $message = '')
 * @method static void allNullOrNatural($value, $message = '')
 * @method static void nullOrBoolean($value, $message = '')
 * @method static void allBoolean($value, $message = '')
 * @method static void allNullOrBoolean($value, $message = '')
 * @method static void nullOrScalar($value, $message = '')
 * @method static void allScalar($value, $message = '')
 * @method static void allNullOrScalar($value, $message = '')
 * @method static void nullOrObject($value, $message = '')
 * @method static void allObject($value, $message = '')
 * @method static void allNullOrObject($value, $message = '')
 * @method static void nullOrResource($value, $type = null, $message = '')
 * @method static void allResource($value, $type = null, $message = '')
 * @method static void allNullOrResource($value, $type = null, $message = '')
 * @method static void nullOrIsCallable($value, $message = '')
 * @method static void allIsCallable($value, $message = '')
 * @method static void allNullOrIsCallable($value, $message = '')
 * @method static void nullOrIsArray($value, $message = '')
 * @method static void allIsArray($value, $message = '')
 * @method static void allNullOrIsArray($value, $message = '')
 * @method static void nullOrIsTraversable($value, $message = '')
 * @method static void allIsTraversable($value, $message = '')
 * @method static void allNullOrIsTraversable($value, $message = '')
 * @method static void nullOrIsArrayAccessible($value, $message = '')
 * @method static void allIsArrayAccessible($value, $message = '')
 * @method static void allNullOrIsArrayAccessible($value, $message = '')
 * @method static void nullOrIsCountable($value, $message = '')
 * @method static void allIsCountable($value, $message = '')
 * @method static void allNullOrIsCountable($value, $message = '')
 * @method static void nullOrIsIterable($value, $message = '')
 * @method static void allIsIterable($value, $message = '')
 * @method static void allNullOrIsIterable($value, $message = '')
 * @method static void nullOrIsInstanceOf($value, $class, $message = '')
 * @method static void allIsInstanceOf($value, $class, $message = '')
 * @method static void allNullOrIsInstanceOf($value, $class, $message = '')
 * @method static void nullOrNotInstanceOf($value, $class, $message = '')
 * @method static void allNotInstanceOf($value, $class, $message = '')
 * @method static void allNullOrNotInstanceOf($value, $class, $message = '')
 * @method static void nullOrIsInstanceOfAny($value, $classes, $message = '')
 * @method static void allIsInstanceOfAny($value, $classes, $message = '')
 * @method static void allNullOrIsInstanceOfAny($value, $classes, $message = '')
 * @method static void nullOrIsAOf($value, $class, $message = '')
 * @method static void allIsAOf($value, $class, $message = '')
 * @method static void allNullOrIsAOf($value, $class, $message = '')
 * @method static void nullOrIsNotA($value, $class, $message = '')
 * @method static void allIsNotA($value, $class, $message = '')
 * @method static void allNullOrIsNotA($value, $class, $message = '')
 * @method static void nullOrIsAnyOf($value, $classes, $message = '')
 * @method static void allIsAnyOf($value, $classes, $message = '')
 * @method static void allNullOrIsAnyOf($value, $classes, $message = '')
 * @method static void nullOrIsEmpty($value, $message = '')
 * @method static void allIsEmpty($value, $message = '')
 * @method static void allNullOrIsEmpty($value, $message = '')
 * @method static void nullOrNotEmpty($value, $message = '')
 * @method static void allNotEmpty($value, $message = '')
 * @method static void allNullOrNotEmpty($value, $message = '')
 * @method static void allNull($value, $message = '')
 * @method static void allNotNull($value, $message = '')
 * @method static void nullOrTrue($value, $message = '')
 * @method static void allTrue($value, $message = '')
 * @method static void allNullOrTrue($value, $message = '')
 * @method static void nullOrFalse($value, $message = '')
 * @method static void allFalse($value, $message = '')
 * @method static void allNullOrFalse($value, $message = '')
 * @method static void nullOrNotFalse($value, $message = '')
 * @method static void allNotFalse($value, $message = '')
 * @method static void allNullOrNotFalse($value, $message = '')
 * @method static void nullOrIp($value, $message = '')
 * @method static void allIp($value, $message = '')
 * @method static void allNullOrIp($value, $message = '')
 * @method static void nullOrIpv4($value, $message = '')
 * @method static void allIpv4($value, $message = '')
 * @method static void allNullOrIpv4($value, $message = '')
 * @method static void nullOrIpv6($value, $message = '')
 * @method static void allIpv6($value, $message = '')
 * @method static void allNullOrIpv6($value, $message = '')
 * @method static void nullOrEmail($value, $message = '')
 * @method static void allEmail($value, $message = '')
 * @method static void allNullOrEmail($value, $message = '')
 * @method static void nullOrUniqueValues($values, $message = '')
 * @method static void allUniqueValues($values, $message = '')
 * @method static void allNullOrUniqueValues($values, $message = '')
 * @method static void nullOrEq($value, $expect, $message = '')
 * @method static void allEq($value, $expect, $message = '')
 * @method static void allNullOrEq($value, $expect, $message = '')
 * @method static void nullOrNotEq($value, $expect, $message = '')
 * @method static void allNotEq($value, $expect, $message = '')
 * @method static void allNullOrNotEq($value, $expect, $message = '')
 * @method static void nullOrSame($value, $expect, $message = '')
 * @method static void allSame($value, $expect, $message = '')
 * @method static void allNullOrSame($value, $expect, $message = '')
 * @method static void nullOrNotSame($value, $expect, $message = '')
 * @method static void allNotSame($value, $expect, $message = '')
 * @method static void allNullOrNotSame($value, $expect, $message = '')
 * @method static void nullOrGreaterThan($value, $limit, $message = '')
 * @method static void allGreaterThan($value, $limit, $message = '')
 * @method static void allNullOrGreaterThan($value, $limit, $message = '')
 * @method static void nullOrGreaterThanEq($value, $limit, $message = '')
 * @method static void allGreaterThanEq($value, $limit, $message = '')
 * @method static void allNullOrGreaterThanEq($value, $limit, $message = '')
 * @method static void nullOrLessThan($value, $limit, $message = '')
 * @method static void allLessThan($value, $limit, $message = '')
 * @method static void allNullOrLessThan($value, $limit, $message = '')
 * @method static void nullOrLessThanEq($value, $limit, $message = '')
 * @method static void allLessThanEq($value, $limit, $message = '')
 * @method static void allNullOrLessThanEq($value, $limit, $message = '')
 * @method static void nullOrRange($value, $min, $max, $message = '')
 * @method static void allRange($value, $min, $max, $message = '')
 * @method static void allNullOrRange($value, $min, $max, $message = '')
 * @method static void nullOrOneOf($value, $values, $message = '')
 * @method static void allOneOf($value, $values, $message = '')
 * @method static void allNullOrOneOf($value, $values, $message = '')
 * @method static void nullOrInArray($value, $values, $message = '')
 * @method static void allInArray($value, $values, $message = '')
 * @method static void allNullOrInArray($value, $values, $message = '')
 * @method static void nullOrContains($value, $subString, $message = '')
 * @method static void allContains($value, $subString, $message = '')
 * @method static void allNullOrContains($value, $subString, $message = '')
 * @method static void nullOrNotContains($value, $subString, $message = '')
 * @method static void allNotContains($value, $subString, $message = '')
 * @method static void allNullOrNotContains($value, $subString, $message = '')
 * @method static void nullOrNotWhitespaceOnly($value, $message = '')
 * @method static void allNotWhitespaceOnly($value, $message = '')
 * @method static void allNullOrNotWhitespaceOnly($value, $message = '')
 * @method static void nullOrStartsWith($value, $prefix, $message = '')
 * @method static void allStartsWith($value, $prefix, $message = '')
 * @method static void allNullOrStartsWith($value, $prefix, $message = '')
 * @method static void nullOrNotStartsWith($value, $prefix, $message = '')
 * @method static void allNotStartsWith($value, $prefix, $message = '')
 * @method static void allNullOrNotStartsWith($value, $prefix, $message = '')
 * @method static void nullOrStartsWithLetter($value, $message = '')
 * @method static void allStartsWithLetter($value, $message = '')
 * @method static void allNullOrStartsWithLetter($value, $message = '')
 * @method static void nullOrEndsWith($value, $suffix, $message = '')
 * @method static void allEndsWith($value, $suffix, $message = '')
 * @method static void allNullOrEndsWith($value, $suffix, $message = '')
 * @method static void nullOrNotEndsWith($value, $suffix, $message = '')
 * @method static void allNotEndsWith($value, $suffix, $message = '')
 * @method static void allNullOrNotEndsWith($value, $suffix, $message = '')
 * @method static void nullOrRegex($value, $pattern, $message = '')
 * @method static void allRegex($value, $pattern, $message = '')
 * @method static void allNullOrRegex($value, $pattern, $message = '')
 * @method static void nullOrNotRegex($value, $pattern, $message = '')
 * @method static void allNotRegex($value, $pattern, $message = '')
 * @method static void allNullOrNotRegex($value, $pattern, $message = '')
 * @method static void nullOrUnicodeLetters($value, $message = '')
 * @method static void allUnicodeLetters($value, $message = '')
 * @method static void allNullOrUnicodeLetters($value, $message = '')
 * @method static void nullOrAlpha($value, $message = '')
 * @method static void allAlpha($value, $message = '')
 * @method static void allNullOrAlpha($value, $message = '')
 * @method static void nullOrDigits($value, $message = '')
 * @method static void allDigits($value, $message = '')
 * @method static void allNullOrDigits($value, $message = '')
 * @method static void nullOrAlnum($value, $message = '')
 * @method static void allAlnum($value, $message = '')
 * @method static void allNullOrAlnum($value, $message = '')
 * @method static void nullOrLower($value, $message = '')
 * @method static void allLower($value, $message = '')
 * @method static void allNullOrLower($value, $message = '')
 * @method static void nullOrUpper($value, $message = '')
 * @method static void allUpper($value, $message = '')
 * @method static void allNullOrUpper($value, $message = '')
 * @method static void nullOrLength($value, $length, $message = '')
 * @method static void allLength($value, $length, $message = '')
 * @method static void allNullOrLength($value, $length, $message = '')
 * @method static void nullOrMinLength($value, $min, $message = '')
 * @method static void allMinLength($value, $min, $message = '')
 * @method static void allNullOrMinLength($value, $min, $message = '')
 * @method static void nullOrMaxLength($value, $max, $message = '')
 * @method static void allMaxLength($value, $max, $message = '')
 * @method static void allNullOrMaxLength($value, $max, $message = '')
 * @method static void nullOrLengthBetween($value, $min, $max, $message = '')
 * @method static void allLengthBetween($value, $min, $max, $message = '')
 * @method static void allNullOrLengthBetween($value, $min, $max, $message = '')
 * @method static void nullOrFileExists($value, $message = '')
 * @method static void allFileExists($value, $message = '')
 * @method static void allNullOrFileExists($value, $message = '')
 * @method static void nullOrFile($value, $message = '')
 * @method static void allFile($value, $message = '')
 * @method static void allNullOrFile($value, $message = '')
 * @method static void nullOrDirectory($value, $message = '')
 * @method static void allDirectory($value, $message = '')
 * @method static void allNullOrDirectory($value, $message = '')
 * @method static void nullOrReadable($value, $message = '')
 * @method static void allReadable($value, $message = '')
 * @method static void allNullOrReadable($value, $message = '')
 * @method static void nullOrWritable($value, $message = '')
 * @method static void allWritable($value, $message = '')
 * @method static void allNullOrWritable($value, $message = '')
 * @method static void nullOrClassExists($value, $message = '')
 * @method static void allClassExists($value, $message = '')
 * @method static void allNullOrClassExists($value, $message = '')
 * @method static void nullOrSubclassOf($value, $class, $message = '')
 * @method static void allSubclassOf($value, $class, $message = '')
 * @method static void allNullOrSubclassOf($value, $class, $message = '')
 * @method static void nullOrInterfaceExists($value, $message = '')
 * @method static void allInterfaceExists($value, $message = '')
 * @method static void allNullOrInterfaceExists($value, $message = '')
 * @method static void nullOrImplementsInterface($value, $interface, $message = '')
 * @method static void allImplementsInterface($value, $interface, $message = '')
 * @method static void allNullOrImplementsInterface($value, $interface, $message = '')
 * @method static void nullOrPropertyExists($classOrObject, $property, $message = '')
 * @method static void allPropertyExists($classOrObject, $property, $message = '')
 * @method static void allNullOrPropertyExists($classOrObject, $property, $message = '')
 * @method static void nullOrPropertyNotExists($classOrObject, $property, $message = '')
 * @method static void allPropertyNotExists($classOrObject, $property, $message = '')
 * @method static void allNullOrPropertyNotExists($classOrObject, $property, $message = '')
 * @method static void nullOrMethodExists($classOrObject, $method, $message = '')
 * @method static void allMethodExists($classOrObject, $method, $message = '')
 * @method static void allNullOrMethodExists($classOrObject, $method, $message = '')
 * @method static void nullOrMethodNotExists($classOrObject, $method, $message = '')
 * @method static void allMethodNotExists($classOrObject, $method, $message = '')
 * @method static void allNullOrMethodNotExists($classOrObject, $method, $message = '')
 * @method static void nullOrKeyExists($array, $key, $message = '')
 * @method static void allKeyExists($array, $key, $message = '')
 * @method static void allNullOrKeyExists($array, $key, $message = '')
 * @method static void nullOrKeyNotExists($array, $key, $message = '')
 * @method static void allKeyNotExists($array, $key, $message = '')
 * @method static void allNullOrKeyNotExists($array, $key, $message = '')
 * @method static void nullOrValidArrayKey($value, $message = '')
 * @method static void allValidArrayKey($value, $message = '')
 * @method static void allNullOrValidArrayKey($value, $message = '')
 * @method static void nullOrCount($array, $number, $message = '')
 * @method static void allCount($array, $number, $message = '')
 * @method static void allNullOrCount($array, $number, $message = '')
 * @method static void nullOrMinCount($array, $min, $message = '')
 * @method static void allMinCount($array, $min, $message = '')
 * @method static void allNullOrMinCount($array, $min, $message = '')
 * @method static void nullOrMaxCount($array, $max, $message = '')
 * @method static void allMaxCount($array, $max, $message = '')
 * @method static void allNullOrMaxCount($array, $max, $message = '')
 * @method static void nullOrCountBetween($array, $min, $max, $message = '')
 * @method static void allCountBetween($array, $min, $max, $message = '')
 * @method static void allNullOrCountBetween($array, $min, $max, $message = '')
 * @method static void nullOrIsList($array, $message = '')
 * @method static void allIsList($array, $message = '')
 * @method static void allNullOrIsList($array, $message = '')
 * @method static void nullOrIsNonEmptyList($array, $message = '')
 * @method static void allIsNonEmptyList($array, $message = '')
 * @method static void allNullOrIsNonEmptyList($array, $message = '')
 * @method static void nullOrIsMap($array, $message = '')
 * @method static void allIsMap($array, $message = '')
 * @method static void allNullOrIsMap($array, $message = '')
 * @method static void nullOrIsNonEmptyMap($array, $message = '')
 * @method static void allIsNonEmptyMap($array, $message = '')
 * @method static void allNullOrIsNonEmptyMap($array, $message = '')
 * @method static void nullOrUuid($value, $message = '')
 * @method static void allUuid($value, $message = '')
 * @method static void allNullOrUuid($value, $message = '')
 * @method static void nullOrThrows($expression, $class = 'Exception', $message = '')
 * @method static void allThrows($expression, $class = 'Exception', $message = '')
 * @method static void allNullOrThrows($expression, $class = 'Exception', $message = '')
 */
final class Assert
{
    private static ?AssertAdapterInterface $adapter = null;

    public static function use(AssertAdapterInterface $adapter): void
    {
        self::$adapter = $adapter;
    }

    public static function dateTimeFormat(string $value, string $format, string $message = ''): void
    {
        $date = \DateTime::createFromFormat($format, $value);
        $errors = \DateTime::getLastErrors();

        if ($date === false || ($errors['error_count'] ?? 0) > 0) {
            throw new AssertFailedException(
                sprintf(
                    '%s is not a valid date/time format of %s: %s',
                    $value,
                    $format,
                    self::resolveMessage($message, implode(', ', $errors['errors'] ?? []))
                )
            );
        }
    }

    public static function isXml(string $xml, string $message = ''): void
    {
        libxml_use_internal_errors(true);
        libxml_clear_errors();

        $reader = XMLReader::XML($xml, null, LIBXML_NONET);

        if (!$reader instanceof XMLReader) {
            throw new AssertFailedException(self::resolveMessage($message, 'Given value is not a valid XML.'));
        }

        $previous = null;

        while ($reader->read()) {
            if ($reader->nodeType === XMLReader::TEXT && trim($reader->value) !== '') {
                if ($previous === XMLReader::END_ELEMENT) {
                    throw new AssertFailedException(
                        self::resolveMessage($message, "Unexpected free text '{$reader->value}' inside XML.")
                    );
                }
            }

            $previous = $reader->nodeType;
        }

        if (($err = libxml_get_last_error()) !== false) {
            $line = $err->line ?? '?';
            $text = trim($err->message);

            throw new AssertFailedException(
                self::resolveMessage($message, "XML parsing error at line {$line}: {$text}")
            );
        }
    }

    public static function xmlMatchesSchema(string $xml, string $schemaPath, string $message = ''): void
    {
        self::fileExists($schemaPath);
        self::isXml($xml);

        libxml_use_internal_errors(true);
        libxml_clear_errors();

        $reader = XMLReader::XML($xml, null, LIBXML_NONET);

        if (!$reader instanceof XMLReader) {
            throw new AssertFailedException(self::resolveMessage($message, 'Given value is not a valid XML.'));
        }

        if (@$reader->setSchema($schemaPath) === true) {
            @$reader->read();
        }

        $errors = libxml_get_errors();

        if (!empty($errors)) {
            $errorMessages = array_map(
                static fn ($error) => sprintf('[Line %d] %s', $error->line, trim($error->message)),
                $errors
            );
            throw new AssertFailedException(
                self::resolveMessage(
                    $message,
                    sprintf(
                        'XML schema validation failed: %s',
                        implode('; ', $errorMessages)
                    )
                )
            );
        }
    }

    private static function resolveMessage(?string $message, string $default): string
    {
        return $message !== null && $message !== '' ? $message : $default;
    }

    /**
     * @param mixed[] $args
     * @throws AssertFailedException
     */
    public static function __callStatic(string $method, array $args): void
    {
        try {
            if (self::$adapter === null) {
                throw new AssertFailedException('No assertion adapter has been set.');
            }
            self::$adapter::$method(...$args); //@phpstan-ignore-line Dynamic static method call
        } catch (\InvalidArgumentException $e) {
            throw new AssertFailedException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
