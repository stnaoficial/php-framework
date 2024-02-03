<?php

namespace Oraculum\Cli\Components;

use Oraculum\Cli\Support\Ansi as AnsiSupport;
use Oraculum\Contracts\Stringable;
use Oraculum\Support\Primitives\PrimitiveObject;

final class Panel extends PrimitiveObject implements Stringable
{
    /**
     * @var string The messaeg to be displayed in the panel.
     */
    private $message;

    /**
     * @var array<string, string> The ANSI character symbols to be used.
     */
    private $symbols;

    /**
     * Create a new instance of the class.
     * 
     * @param string $message The message to be displayed in the panel.
     * @param string $theme   The ANSI theme to use.
     * 
     * @return void
     */
    public function __construct($message = '', $theme = 'classic')
    {
        $this->message = trim($message);
        $this->symbols = AnsiSupport::getBoxCharacterSymbols($theme);
    }

    /**
     * Get the computed size.
     * 
     * @return int The computed size.
     */
    private function getComputedSize()
    {
        $size = 0;

        foreach (explode(PHP_EOL, $this->message) as $line) {
            // Get the length of the column without ANSI codes for a more
            // accurate comparison.
            $len = strlen(AnsiSupport::normalize(trim($line)));

            // Set the length if it's bigger than the current.
            $size < $len && $size = $len;
        }

        return $size;
    }

    /**
     * Get the top line.
     * 
     * @param int $size The computed size.
     * 
     * @return string The top line.
     */
    private function getTopLine($size)
    {
        return (
            $this->symbols['top-left'] .
            str_repeat($this->symbols['top'], $size + 2) .
            $this->symbols['top-right'] .
            PHP_EOL
        );
    }

    /**
     * Get the row line.
     * 
     * @param int    $size The computed size.
     * @param string $line The line of the message.
     * 
     * @return string The row line.
     */
    private function getMessageLine($size, $line)
    {
        // Trims the line of the message to avoid whitespace issues.
        $line = trim($line);

        // This helps to avoid whitespace issues for styled texts by removing
        // ANSI codes.
        $diff = strlen($line) - strlen(AnsiSupport::normalize($line));

        // Sets the length to fill as the difference between the column
        // escaped and non-escaped size.
        $len = $size + $diff;

        return (
            $this->symbols['left'] .
            str_pad($line, $len, ' ', STR_PAD_RIGHT) .
            $this->symbols['right'] .
            PHP_EOL
        );
    }

    /**
     * Get the bottom line.
     * 
     * @param int $size The computed size.
     * 
     * @return string The bottom line.
     */
    private function getBottomLine($size)
    {
        return (
            $this->symbols['bottom-left'] .
            str_repeat($this->symbols['bottom'], $size + 2) .
            $this->symbols['bottom-right']
        );
    }

    /**
     * Appends a line to the message.
     * 
     * @param string $line  The line to append.
     * @param int    $break The number of new lines to break.
     * 
     * @return void
     */
    public function line($line = '', $break = 0)
    {
        $this->message .= $line . str_repeat(PHP_EOL, $break);
    }

    /**
	 * Gets a string representation of the object.
     * 
     * @return string Returns the `string` representation of the object.
	 */
	public function __toString(): string
    {
        $size = $this->getComputedSize();

        $string = $this->getTopLine($size);

        foreach (explode(PHP_EOL, $this->message) as $line) {
            $string .= $this->getMessageLine($size, $line);
        }

        $string .= $this->getBottomLine($size);

        return $string;
    }

    /**
     * Gets a string representation of the object.
     * 
     * @return string Returns the `string` representation of the object.
     */
    public function toString()
    {
        return $this->__toString();
    }
}