<?php

namespace App\Advent\Days;

use App\Advent\Day;

class Day13 {
    use Day;

    public $title = 'Day 13: Shuttle Search';

    /**
     * Complete the puzzle.
     *
     * @return self
     */
    public function __invoke(): self
    {
        $this->part1();
        $this->part2();
        return $this;
    }

    /**
     * Complete part one of the puzzle.
     *
     * @return void
     */
    private function part1()
    {
        $this->start();

        $lines = explode("\n", $this->getInput());

        $arrival = (int) $lines[0];
        $buses = str_getcsv($lines[1]);
        $departures = [];

        foreach ($buses as $bus) {
            if ($bus === 'x') continue;

            $departs = 0;
            $loop = 1;

            while ($departs < $arrival) {
                $departs = (int) $bus * $loop;
                $loop++;
            }

            $departures[$bus] = $departs;
        }

        $busId = array_keys($departures, min($departures))[0];
        $wait = min($departures) - $arrival;

        $answer = $busId * $wait;

        $this->finish();

        $this->addPart([
            'table' => [
                'headings' => ['Part 1: Answer'],
                'data' => [[$answer]],
            ],
            'time' => $this->getTime(),
        ]);
    }

    /**
     * Complete part two of the puzzle.
     *
     * @return void
     */
    private function part2()
    {
        $this->start();

        $lines = explode("\n", $this->getInput());

        $buses = str_getcsv($lines[1]);
        $min = 100000000000000;
        $times = (int) ($min / (int) $buses[0]);
        $result = null;
        $length = count($buses);

        while (! $result) {
            $sequenced = [];
            $current = $buses[0] * $times;
            $sequenced[] = $current;

            foreach ($buses as $i => $bus) {
                if ($i > 0) {
                    if ($this->canDepartNextMinute($bus, $current)) {
                        $current++;
                        $sequenced[] = $current;
                    } else {
                        break;
                    }
                }
            }

            $times++;

            if (count($sequenced) > 37) {
                echo count($sequenced).": $current \n";
            }

            if (count($sequenced) === $length) {
                $result = $sequenced[0];
            }
        }

        $this->finish();

        $this->addPart([
            'table' => [
                'headings' => ['Part 2: Answer'],
                'data' => [[$result]],
            ],
            'time' => $this->getTime(),
        ]);
    }

    /**
     * Check if a given bus can depart the following minute.
     *
     * @param string $id
     * @param integer $previous
     * @return boolean
     */
    private function canDepartNextMinute(string $id, int $previous)
    {
        if ($id === 'x') return true;

        return ($previous + 1) % (int) $id == 0;
    }

    /**
     * Get the input for the puzzle.
     *
     * @return string
     */
    private function getInput(): string
    {
        return '1002460
29,x,x,x,x,x,x,x,x,x,x,x,x,x,x,x,x,x,x,41,x,x,x,x,x,x,x,x,x,601,x,x,x,x,x,x,x,23,x,x,x,x,13,x,x,x,17,x,19,x,x,x,x,x,x,x,x,x,x,x,463,x,x,x,x,x,x,x,x,x,x,x,x,x,x,x,x,x,x,x,x,x,x,x,x,x,x,x,x,x,x,x,x,x,x,x,x,37';
    }
}
