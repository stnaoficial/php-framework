<?php

namespace Oraculum\Cli\Support;

use InvalidArgumentException;
use Oraculum\Support\Traits\NonInstantiable;

final class Ansi
{
    use NonInstantiable;

    public const DECORATION_BOLD           = 1;
    public const DECORATION_ITALIC         = 2;
    public const DECORATION_UNDERLINE      = 4;
    public const DECORATION_STRIKETHROUGH  = 8;

    public const FOREGROUND_BLACK          = 16;
    public const FOREGROUND_RED            = 32;
    public const FOREGROUND_GREEN          = 64;
    public const FOREGROUND_YELLOW         = 128;
    public const FOREGROUND_BLUE           = 256;
    public const FOREGROUND_MAGENTA        = 512;
    public const FOREGROUND_CYAN           = 1024;
    public const FOREGROUND_WHITE          = 2048;
    public const FOREGROUND_BRIGHT_BLACK   = 4096;
    public const FOREGROUND_BRIGHT_RED     = 8192;
    public const FOREGROUND_BRIGHT_GREEN   = 16384;
    public const FOREGROUND_BRIGHT_YELLOW  = 32768;
    public const FOREGROUND_BRIGHT_BLUE    = 65536;
    public const FOREGROUND_BRIGHT_MAGENTA = 131072;
    public const FOREGROUND_BRIGHT_CYAN    = 262144;
    public const FOREGROUND_BRIGHT_WHITE   = 524288;

    public const BACKGROUND_BLACK          = 1048576;
    public const BACKGROUND_RED            = 2097152;
    public const BACKGROUND_GREEN          = 4194304;
    public const BACKGROUND_YELLOW         = 8388608;
    public const BACKGROUND_BLUE           = 16777216;
    public const BACKGROUND_MAGENTA        = 33554432;
    public const BACKGROUND_CYAN           = 67108864;
    public const BACKGROUND_WHITE          = 134217728;
    public const BACKGROUND_BRIGHT_BLACK   = 268435456;
    public const BACKGROUND_BRIGHT_RED     = 536870912;
    public const BACKGROUND_BRIGHT_GREEN   = 1073741824;
    public const BACKGROUND_BRIGHT_YELLOW  = 2147483648;
    public const BACKGROUND_BRIGHT_BLUE    = 4294967296;
    public const BACKGROUND_BRIGHT_MAGENTA = 8589934592;
    public const BACKGROUND_BRIGHT_CYAN    = 17179869184;
    public const BACKGROUND_BRIGHT_WHITE   = 34359738368;

    /**
     * @var array<int, int> Defines the associated code for each flag.
     */
    private static $formats = [
        self::DECORATION_BOLD           => 1,
        self::DECORATION_ITALIC         => 3,
        self::DECORATION_UNDERLINE      => 4,
        self::DECORATION_STRIKETHROUGH  => 9,

        self::FOREGROUND_BLACK          => 30,
        self::FOREGROUND_RED            => 31,
        self::FOREGROUND_GREEN          => 32,
        self::FOREGROUND_YELLOW         => 33,
        self::FOREGROUND_BLUE           => 34,
        self::FOREGROUND_MAGENTA        => 35,
        self::FOREGROUND_CYAN           => 36,
        self::FOREGROUND_WHITE          => 37,
        self::FOREGROUND_BRIGHT_BLACK   => 90,
        self::FOREGROUND_BRIGHT_RED     => 91,
        self::FOREGROUND_BRIGHT_GREEN   => 92,
        self::FOREGROUND_BRIGHT_YELLOW  => 93,
        self::FOREGROUND_BRIGHT_BLUE    => 94,
        self::FOREGROUND_BRIGHT_MAGENTA => 95,
        self::FOREGROUND_BRIGHT_CYAN    => 96,
        self::FOREGROUND_BRIGHT_WHITE   => 97,

        self::BACKGROUND_BLACK          => 40,
        self::BACKGROUND_RED            => 41,
        self::BACKGROUND_GREEN          => 42,
        self::BACKGROUND_YELLOW         => 43,
        self::BACKGROUND_BLUE           => 44,
        self::BACKGROUND_MAGENTA        => 45,
        self::BACKGROUND_CYAN           => 46,
        self::BACKGROUND_WHITE          => 47,
        self::BACKGROUND_BRIGHT_BLACK   => 100,
        self::BACKGROUND_BRIGHT_RED     => 101,
        self::BACKGROUND_BRIGHT_GREEN   => 102,
        self::BACKGROUND_BRIGHT_YELLOW  => 103,
        self::BACKGROUND_BRIGHT_BLUE    => 104,
        self::BACKGROUND_BRIGHT_MAGENTA => 105,
        self::BACKGROUND_BRIGHT_CYAN    => 106,
        self::BACKGROUND_BRIGHT_WHITE   => 107
    ];

    /**
     * Formats a message with the specified ANSI code flags.
     *
     * @param string $message The message to be formatted.
     * @param int    $flags   The ANSI code flags to apply.
     * @param array  $codes   The ANSI codes to apply.
     *
     * @return string The formatted message.
     */
    public static function format($message, $flags = 0, $codes = [])
    {
        if ($flags !== 0) {
            foreach (self::$formats as $flag => $format) {
                ($flags & $flag) && $codes[] = $format;
            }
        }

        $codes = implode(';', $codes);

        // e.g. Hello -> [1;35;47mHello[0m
        return sprintf("\e[%sm%s\e[0m", $codes, $message);
    }

    /**
     * Normalizes an message with ANSI codes.
     * 
     * @param string $message The message to be normalized.
     * 
     * @return string The normalized message.
     */
    public static function normalize($message)
    {
        // e.g. [1;35;47mHello[0m -> Hello
        return preg_replace('#\\x1b[[][^A-Za-z]*[A-Za-z]#', '', $message);
    }

    /**
     * Gets the box character symbols for the specified theme.
     * 
     * @param string $theme The theme to use.
     * 
     * @throws InvalidArgumentException If the theme is invalid.
     * 
     * @return array<int, string> The box character symbols.
     */
    public static function getBoxCharacterSymbols($theme = 'classic')
    {
        return match ($theme) {
            default => throw new InvalidArgumentException(sprintf(
                "Invalid theme [%s]. Please check your arguments and try again.", $theme
            )),
            'classic' => [
                'top'           => '-',
                'top-left'      => '+',
                'top-right'     => '+',
                'top-center'    => '+',
                'bottom'        => '-',
                'bottom-right'  => '+',
                'bottom-left'   => '+',
                'bottom-center' => '+',
                'left'          => '| ',
                'left-center'   => '+',
                'right'         => ' |',
                'right-center'  => '+',
                'vertical'      => '-',
                'horizontal'    => ' | ',
                'middle'        => '+',
            ],
            'single' => [
                'top'           => '─',
                'top-left'      => '┌',
                'top-right'     => '┐',
                'top-center'    => '┬',
                'bottom'        => '─',
                'bottom-left'   => '└',
                'bottom-right'  => '┘',
                'bottom-center' => '┴',
                'left'          => '│ ',
                'left-center'   => '├',
                'right'         => ' │',
                'right-center'  => '┤',
                'vertical'      => '─',
                'horizontal'    => ' │ ',
                'middle'        => '┼',
            ],
            'bold' => [
                'top'           => '━',
                'top-left'      => '┏',
                'top-right'     => '┓',
                'top-center'    => '┳',
                'bottom'        => '━',
                'bottom-right'  => '┛',
                'bottom-left'   => '┗',
                'bottom-center' => '┻',
                'left'          => '┃ ',
                'left-center'   => '┣',
                'right'         => ' ┃',
                'right-center'  => '┫',
                'vertical'      => '━',
                'horizontal'    => ' ┃ ',
                'middle'        => '╋',
            ],
            'double' => [
                'top'           => '═',
                'top-left'      => '╔',
                'top-right'     => '╗',
                'top-center'    => '╦',
                'bottom'        => '═',
                'bottom-right'  => '╝',
                'bottom-left'   => '╚',
                'bottom-center' => '╩',
                'left'          => '║ ',
                'left-center'   => '╠',
                'right'         => ' ║',
                'right-center'  => '╣',
                'vertical'      => '═',
                'horizontal'    => ' ║ ',
                'middle'        => '╬',
            ],
            'round' => [
                'top'           => '─',
                'top-left'      => '╭',
                'top-right'     => '╮',
                'top-center'    => '┬',
                'bottom'        => '─',
                'bottom-right'  => '╯',
                'bottom-left'   => '╰',
                'bottom-center' => '┴',
                'left'          => '│ ',
                'left-center'   => '├',
                'right'         => ' │',
                'right-center'  => '┤',
                'vertical'      => '─',
                'horizontal'    => ' │ ',
                'middle'        => '┼',
            ],
            'dashed' => [
                'top'           => '╌',
                'top-left'      => '┌',
                'top-right'     => '┐',
                'top-center'    => '┬',
                'bottom'        => '╌',
                'bottom-left'   => '└',
                'bottom-right'  => '┘',
                'bottom-center' => '┴',
                'left'          => '╎ ',
                'left-center'   => '├',
                'right'         => ' ╎',
                'right-center'  => '┤',
                'vertical'      => '╌',
                'horizontal'    => ' ╎ ',
                'middle'        => '┼',
            ],
            'dashed-bold' => [
                'top'           => '╍',
                'top-left'      => '┏',
                'top-right'     => '┓',
                'top-center'    => '┳',
                'bottom'        => '╍',
                'bottom-right'  => '┛',
                'bottom-left'   => '┗',
                'bottom-center' => '┻',
                'left'          => '╏ ',
                'left-center'   => '┣',
                'right'         => ' ╏',
                'right-center'  => '┫',
                'vertical'      => '╍',
                'horizontal'    => ' ╏ ',
                'middle'        => '╋',
            ]
        };
    }
}