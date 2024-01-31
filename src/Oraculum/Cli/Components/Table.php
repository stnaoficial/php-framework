<?php

namespace Oraculum\Cli\Components;

use Oraculum\Cli\Console;
use Oraculum\Contracts\Stringable;
use Oraculum\Support\Primitives\PrimitiveObject;

final class Table extends PrimitiveObject implements Stringable
{
    /**
     * @var array An bidimensional `array` of data.
     */
    private $data;

    /**
     * @var array<string, string> The characters to be used in the table.
     */
    private $chars = [
        'top'          => '═',
        'top-mid'      => '╤',
        'top-left'     => '╔',
        'top-right'    => '╗',
        'bottom'       => '═',
        'bottom-mid'   => '╧',
        'bottom-left'  => '╚',
        'bottom-right' => '╝',
        'left'         => '║ ',
        'left-mid'     => '╟',
        'mid'          => '─',
        'mid-mid'      => '┼',
        'right'        => ' ║',
        'right-mid'    => '╢',
        'middle'       => ' │ ',
    ];

    /**
     * Create a new instance of the class.
     * 
     * @param array $data The data to be used in the table.
     * 
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the computed column sizes.
     * 
     * @return array<int, int> The computed column sizes.
     */
    private function getComputedColumnSizes()
    {
        $key = 0;

        $sizes = [];

        foreach ($this->data as $row) {
            if (!is_array($row)) {
                continue;
            }

            foreach ($row as $col) {
                // Set the column size to 0 if it doesn't exist.
                !isset($sizes[$key]) && $sizes[$key] = 0;

                // Get the length of the column without ANSI codes for a more
                // accurate comparison.
                $len = strlen(Console::normalize($col));

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
            $line[] = str_repeat($this->chars['top'], $size + 2);
        }

        return (
            $this->chars['top-left'] .
            implode($this->chars['top-mid'], $line) .
            $this->chars['top-right'] .
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
            $diff = strlen($col) - strlen(Console::normalize($col));

            // Sets the length to fill as the difference between the column
            // escaped and non-escaped size.
            $len = $size + $diff;

            $line[] = str_pad($col, $len, ' ', STR_PAD_RIGHT);
        }

        return (
            $this->chars['left'] .
            implode($this->chars['middle'], $line) .
            $this->chars['right'] .
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
            $line[] = str_repeat($this->chars['mid'], $size + 2);
        }

        return (
            $this->chars['left-mid'] .
            implode($this->chars['mid-mid'], $line) .
            $this->chars['right-mid'] .
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
            $line[] = str_repeat($this->chars['bottom'], $size + 2);
        }

        return (
            $this->chars['bottom-left'] .
            implode($this->chars['bottom-mid'], $line) .
            $this->chars['bottom-right']
        );
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
            if (!is_array($row)) {
                continue;
            }

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