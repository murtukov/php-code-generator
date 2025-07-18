<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

/**
 * Static config to be used by all generator components.
 */
class Config
{
    public static string $indent = '    ';
    public static bool $shortenQualifiers = true;
    public static bool $manageUseStatements = true;
    public static string $suppressSymbol = '@';

    /**
     * @var ConverterInterface[]
     */
    private static array $customStringifiers = [
        // e.g.: 'App\Stringifiers\ExpressionStringifier' => object,
    ];

    /**
     * A map of FQCNs and their types registered as custom stringifiers.
     */
    private static array $customStringifiersTypeMap = [
        // e.g.: 'string' => [App\Stringifiers\ExpressionStringifier, App\Stringifiers\AnotherStringifier],
    ];

    /**
     * Registers user defined stringifiers.
     */
    public static function registerConverter(ConverterInterface $converter, string $type): void
    {
        $fqcn = get_class($converter);

        self::$customStringifiers[$fqcn] = $converter;
        self::$customStringifiersTypeMap[$type][] = $fqcn;
    }

    /**
     * Unregister a previously registered custom stringifier.
     *
     * @param string $fqcn - Fully qualified class name
     */
    public static function unregisterConverter(string $fqcn): void
    {
        // Remove instance
        unset(self::$customStringifiers[$fqcn]);
        // Remove map entry
        $type = array_search($fqcn, self::$customStringifiersTypeMap);
        unset(self::$customStringifiersTypeMap[$type]);
    }

    /**
     * Returns an instance of a registered custom stringifier.
     *
     * @return ConverterInterface|null
     */
    public static function getConverter(string $fqcn): ?object
    {
        return self::$customStringifiers[$fqcn] ?? null;
    }

    public static function getConverterClasses(string $type): array
    {
        return self::$customStringifiersTypeMap[$type] ?? [];
    }
}
