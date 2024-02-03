<?php

namespace Oraculum\Cli\Components;

use Oraculum\Cli\Support\Ansi as AnsiSupport;
use Oraculum\Contracts\Stringable;
use Oraculum\Support\Primitives\PrimitiveObject;
use UnexpectedValueException;

final class Table extends PrimitiveObject implements Stringable
{
    /**
     * @var array An bidimensional `array` of data.
     */
    private $data;

    /**
     * @var array<string, string> The ANSI character symbols to be used.
     */
    private $symbols;

    /**
     * Create a new instance of the class.
     * 
     * @param array $data   The data to be used in the table.
     * @param string $theme The ANSI theme to use.
     * 
     * @return void
     */
    public function __construct($data = [], $theme = 'classic')
    {
        $this->data    = $data;
        $this->symbols = AnsiSupport::getBoxCharacterSymbols($theme);
    }

    /**
     * Get the computed column sizes.
     * 
     * @throws UnexpectedValueException If the table format is invalid.
     * 
     * @return array<int, int> The computed column sizes.
     */
    private function getComputedColumnSizes()
    {
        $key = 0;

        $sizes = [];

        foreach ($this->data as $row) {
            if (!is_array($row)) {
                throw new UnexpectedValueException(
                    'The table row must be an array.'
                );
            }

            foreach ($row as $col) {
                if (!is_string($col)) {
                    throw new UnexpectedValueException(
                        'The table column must be a string.'
                    );
                }

                // Set the column size to 0 if it doesn't exist.
                !isset($sizes[$key]) && $sizes[$key] = 0;

                // Get the length of the column without ANSI codes for a more
                // accurate comparison.
                $len = strlen(AnsiSupport::normalize($col));

                // Set the length if it's bigger than the current.
                $sizes[$key] < $len && $sizes[$key] = $len;

                $key++;
            }

            $key = 0;
        }

        return $sizes;
    }

    /**
     * Get the top line.
     * 
     * @param array<int, int> $sizes The computed column sizes.
     * 
     * @return string The top line.
     */
    private function getTopLine($sizes)
    {
        $line = [];

        foreach ($sizes as $size) {
            $line[] = str_repeat($this->symbols['top'], $size + 2);
        }

        return (
            $this->symbols['top-left'] .
            implode($this->symbols['top-center'], $line) .
            $this->symbols['top-right'] .
            PHP_EOL
        );
    }

    /**
     * Get the row line.
     * 
     * @param array<int, int> $sizes The computed column sizes.
     * @param array           $row   The row data.
     * 
     * @return string The row line.
     */
    private function getRowLine($sizes, $row)
    {
        $line = [];

        foreach ($sizes as $key => $size) {
            $col = isset($row[$key])? $row[$key] : '';

            // This helps to avoid whitespace issues for styled texts by removing
            // ANSI codes.
            $diff = strlen($col) - strlen(AnsiSupport::normalize($col));

            // Sets the length to fill as the difference between the column
            // escaped and non-escaped size.
            $len = $size + $diff;

            $line[] = str_pad($col, $len, ' ', STR_PAD_RIGHT);
        }

        return (
            $this->symbols['left'] .
            implode($this->symbols['horizontal'], $line) .
            $this->symbols['right'] .
            PHP_EOL
        );
    }

    /**
     * Get the divider line.
     * 
     * @param array<int, int> $sizes The computed column sizes.
     * 
     * @return string The divider line.
     */
    private function getDividerLine($sizes)
    {
        $line = [];

        foreach ($sizes as $size) {
            $line[] = str_repeat($this->symbols['vertical'], $size + 2);
        }

        return (
            $this->symbols['left-center'] .
            implode($this->symbols['middle'], $line) .
            $this->symbols['right-center'] .
            PHP_EOL
        );
    }

    /**
     * Get the bottom line.
     * 
     * @param array<int, int> $sizes The computed column sizes.
     * 
     * @return string The bottom line.
     */
    private function getBottomLine($sizes)
    {
        $line = [];

        foreach ($sizes as $size) {
            $line[] = str_repeat($this->symbols['bottom'], $size + 2);
        }

        return (
            $this->symbols['bottom-left'] .
            implode($this->symbols['bottom-center'], $line) .
            $this->symbols['bottom-right']
        );
    }

    /**
     * Appends a row to the table.
     * 
     * @param array<int, string> $columns The row columns.
     * 
     * @return void
     */
    public function row($columns = [])
    {
        $this->data[] = $columns;
    }

    /**
	 * Gets a string representation of the object.
     * 
     * @return string Returns the `string` representation of the object.
	 */
	public function __toString(): string
    {
        $sizes = $this->getComputedColumnSizes();

        $lines = [$this->getTopLine($sizes)];

        foreach ($this->data as $key => $row) {
            $lines[] = $this->getRowLine($sizes, $row);

            // Skip the divider for the remaining rows.
            // This makes the first row the head of the table.
            if ($key > 0) {
                continue;
            }

            $lines[] = $this->getDividerLine($sizes);
        }

        $lines[] = $this->getBottomLine($sizes);

        $string = implode('', $lines);

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