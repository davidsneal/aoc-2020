<?php

namespace App\Advent;

trait Day {
    /**
     * The microtime that an execution was started at.
     */
    protected $start;

    /**
     * The time in milliseconds that execution took.
     */
    protected $time;

    /**
     * An array of the parts of the puzzle.
     */
    protected $parts = [];

    /**
     * Set the start time.
     *
     * @return void
     */
    public function start()
    {
        $this->start = microtime(true);
    }

    /**
     * Stop the timer, i.e. calculate and save execution time.
     *
     * @return void
     */
    public function finish()
    {
        $this->time =(microtime(true) - $this->start) * 1000;
    }

    /**
     * Get the time that execution took.
     *
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Get the array of puzzle parts.
     *
     * @return array
     */
    public function getParts(): array
    {
        return $this->parts;
    }

    /**
     * Add a part to the array of parts.
     *
     * @param array $part
     * @return array
     */
    public function addPart(array $part): array
    {
        return $this->parts[] = $part;
    }
}
