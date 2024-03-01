<?php

namespace Oraculum\Cli;

use Oraculum\Support\Primitives\PrimitiveObject;
use UnexpectedValueException;

final class Process extends PrimitiveObject
{
    /**
     * @var int Determine if the output stream should be unblocked.
     */
    public const UNBLOCK_OUTPUT_STREAM = 0;

    /**
     * @var int Determine if the output stream should be skipped.
     */
    public const SKIP_OUTPUT_PIPE = 1;

    /**
     * @var int Determine if the error stream should be skipped.
     */
    public const SKIP_ERROR_PIPE = 2;

    /**
     * @var array The command to execute.
     */
    private $cmd = [];

    /**
     * @var array The process environment variables.
     */
    private $env = [];

    /**
     * @var string The current working directory.
     */
    private $cwd;

    /**
     * @var bool Determine if the process is a TTY.
     */
    private $tty = false;

    /**
     * @var resource The process `resource`.
     */
    private $resource = false;
 
    /**
     * @var array The process information.
     */
    private $info = [];

    /**
     * @var array The process descriptors.
     */
    private $descriptors = [
        0 => ['pipe', 'r'], // Input
        1 => ['pipe', 'w'], // Output
        2 => ['pipe', 'w'], // Error
    ];

    /**
     * @var array The process pipes.
     */
    private $pipes = [];

    /**
     * @var string The output buffer.
     */
    private $outputBuffer = '';

    /**
     * @var string The error buffer.
     */
    private $errorBuffer = '';

    /**
     * Creates a new instance of the class.
     * 
     * @param array $cmd The command to execute.
     * @param array $env The environment variables to pass.
     * 
     * @return void
     */
    public function __construct($cmd = [], $env = [])
    {
        $this->cmd = $cmd;
        $this->env = $env;
        $this->cwd = getcwd();
    }

    /**
     * Destroys the instance of the class.
     * 
     * @return void
     */
    public function __destruct()
    {
        $this->terminate();
    }

    /**
     * Get the command to run.
     * 
     * @return array The command to run.
     */
    public function getCommand()
    {
        return $this->cmd;
    }

    /**
     * Get the environment variables.
     * 
     * @return array The environment variables.
     */
    public function getEnvironment()
    {
        return $this->env;
    }

    /**
     * Determine if the process is a TTY.
     * 
     * @param bool $tty Whether the process is a TTY.
     * 
     * @return void.
     */
    public function tty($tty)
    {
        $this->tty = $tty;
    }

    /**
     * Determine if the process is ready.
     * 
     * @return bool Returns `true` if the process is ready, `false` otherwise.
     */
    public function isReady()
    {
        return is_resource($this->resource);
    }

    /**
     * Determine if the process is running.
     * 
     * @return bool Returns `true` if the process is running, `false` otherwise.
     */
    public function isRunning()
    {
        return isset($this->info['running']) && $this->info['running'];
    }

    /**
     * Determine if the process has a parent PID.
     * 
     * @return bool Returns `true` if the process has a parent PID, `false` otherwise.
     */
    public function hasParentPid()
    {
        return isset($this->info['pid']);
    }

    /**
     * Get the parent PID.
     * 
     * @return int The parent PID.
     */
    public function getParentPid()
    {
        return $this->info['pid'] ?? null;
    }

    /**
     * Get the output buffer.
     * 
     * @return string The output buffer.
     */
    public function getOutputBuffer()
    {
        return $this->outputBuffer;
    }

    /**
     * Get the error buffer.
     * 
     * @return string The error buffer.
     */
    public function getErrorBuffer()
    {
        return $this->errorBuffer;
    }

    /**
     * Update the process information.
     * 
     * @return void
     */
    private function update()
    {
        $this->info = proc_get_status($this->resource);
    }

    /**
     * Process process pipes.
     * 
     * @param int            $pipe    The pipe type.
     * @param Closure|string $handler The process handler.
     * @param int            $flags   The process flags.
     * 
     * @return void
     */
    private function process($pipe, $handler, $flags = 0)
    {
        if (!isset($this->pipes[$pipe]) || !is_resource($this->pipes[$pipe])) {
            return;
        }

        $meta = stream_get_meta_data($this->pipes[$pipe]);

        if (isset($meta['uri']) && !is_readable($meta['uri'])) {
            return;
        }

        if (isset($meta['blocked']) && $meta['blocked'] && ($flags & self::UNBLOCK_OUTPUT_STREAM)) {
            stream_set_blocking($this->pipes[$pipe], false);
        }

        if (!$line = fgets($this->pipes[$pipe])) {
            return;
        }

        match ($pipe) {
            1 => $this->outputBuffer .= $line,
            2 => $this->errorBuffer .= $line,
        };

        call_user_func($handler, $pipe, $line);
    }

    /**
     * Run the process.
     * 
     * @param Closure|string $handler The process handler.
     * @param int            $flags   The process flags.
     * 
     * @return bool Returns `true` if the process was started successfully, `false` otherwise.
     */
    public function run($handler, $flags = 0)
    {
        if (!$this->resource = proc_open(
            $this->cmd,
            $this->descriptors,
            $this->pipes,
            $this->cwd,
            $this->env
        )) {
            throw new UnexpectedValueException(
                "Unable to start process."
            );
        }

        do {
            $this->update();

            if ($flags & ~self::SKIP_OUTPUT_PIPE) {
                $this->process(1, $handler, $flags);
            }

            if ($flags & ~self::SKIP_ERROR_PIPE) {
                $this->process(2, $handler, $flags);
            }

        } while($this->isReady() && $this->isRunning());

        if ($this->tty) {
            return true;
        }

        return $this->terminate();
    }

    /**
     * Terminate the process.
     * 
     * @return bool Returns `true` if the process was terminated successfully, `false` otherwise.
     */
    public function terminate()
    {
        // Close all pipes that were opened during the process execution
        // otherwise, the process will hang forever.
        foreach ($this->pipes as $pipe) {
            is_resource($pipe) && fclose($pipe);
        }

        if (!$this->isReady()) {
            return false;
        }

        // Send SIGTERM to the process and wait for it to terminate in a POSIX 
        // environment.
        if (function_exists('posix_kill')) {
            call_user_func('posix_kill', $this->getParentPid(), SIGTERM);
        }

        if (-1 === $status = proc_close($this->resource) && $this->isReady()) {
            throw new UnexpectedValueException(
                "Unable to terminate process."
            );
        }

        // Flush the process internals.
        $this->info  = [];
        $this->pipes = [];

        return $status !== -1? true : false;
    }
}