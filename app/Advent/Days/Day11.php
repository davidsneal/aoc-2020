<?php

namespace App\Advent\Days;

use App\Advent\Day;

class Day11 {
    use Day;

    public $title = 'Day 11: Seating System';
    private $lines = [];

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

        $this->lines = explode("\n", $this->getInput());

        foreach ($this->lines as $linei => $line) {
            $this->lines[$linei] = str_split($line);
        }

        $changes = true;

        while ($changes) {
            $after = [];

            foreach ($this->lines as $linei => $line) {
                foreach ($line as $seati => $seat) {
                    $new = $seat;

                    switch ($seat) {
                        case 'L':
                            if (! $this->adjacentSeatCount('#', $linei, $seati)) {
                                $new = '#';
                            }
                            break;

                        case '#':
                            if ($this->adjacentSeatCount('#', $linei, $seati) >= 4) {
                                $new = 'L';
                            }
                            break;
                    }

                    $after[$linei][$seati] = $new;
                }
            }

            if ($this->lines === $after) {
                $occupied = $this->getOccupiedCount();
                $changes = false;
            }

            $this->lines = $after;

            $occupied = $this->getOccupiedCount();
        }

        $this->finish();

        $this->addPart([
            'table' => [
                'headings' => ['Part 1: Answer'],
                'data' => [[$occupied]],
            ],
            'time' => $this->getTime(),
        ]);
    }

    /**
     * Get the number of occupied seats.
     *
     * @return integer
     */
    private function getOccupiedCount(): int
    {
        $occupied = 0;

        foreach ($this->lines as $line) {
            foreach ($line as $seat) {
                if ($seat === '#') $occupied++;
            }
        }

        return $occupied;
    }

    /**
     * Check the count of x types of seats adjacent to a given seat.
     *
     * @param string $needle
     * @param integer $linei
     * @param integer $seati
     * @return int
     */
    private function adjacentSeatCount(string $needle, int $linei, int $seati): int
    {
        $adjacents = [];

        $adjacents[] = $this->lines[$linei - 1][$seati - 1] ?? null;
        $adjacents[] = $this->lines[$linei - 1][$seati] ?? null;
        $adjacents[] = $this->lines[$linei - 1][$seati + 1] ?? null;
        $adjacents[] = $this->lines[$linei][$seati - 1] ?? null;
        $adjacents[] = $this->lines[$linei][$seati + 1] ?? null;
        $adjacents[] = $this->lines[$linei + 1][$seati - 1] ?? null;
        $adjacents[] = $this->lines[$linei + 1][$seati] ?? null;
        $adjacents[] = $this->lines[$linei + 1][$seati + 1] ?? null;

        return collect($adjacents)->filter(function($adjacent) use ($needle) {
            return $adjacent === $needle;
        })->count();
    }

    /**
     * Complete part two of the puzzle.
     *
     * @return void
     */
    private function part2()
    {
        $this->start();

        $this->lines = explode("\n", $this->getInput());

        foreach ($this->lines as $linei => $line) {
            $this->lines[$linei] = str_split($line);
        }

        $changes = true;

        while ($changes) {
            $after = [];

            foreach ($this->lines as $linei => $line) {
                foreach ($line as $seati => $seat) {
                    $new = $seat;

                    switch ($seat) {
                        case 'L':
                            if (! $this->visibleSeatCount('#', $linei, $seati)) {
                                $new = '#';
                            }
                            break;

                        case '#':
                            if ($this->visibleSeatCount('#', $linei, $seati) >= 5) {
                                $new = 'L';
                            }
                            break;
                    }

                    $after[$linei][$seati] = $new;
                }
            }

            if ($this->lines === $after) {
                $occupied = $this->getOccupiedCount();
                $changes = false;
            }

            $this->lines = $after;

            $occupied = $this->getOccupiedCount();
        }

        $this->finish();

        $this->addPart([
            'table' => [
                'headings' => ['Part 2: Answer'],
                'data' => [[$occupied]],
            ],
            'time' => $this->getTime(),
        ]);
    }

    /**
     * Get the count of x types of seats visible from a given seat
     *
     * @param string $needle
     * @param integer $linei
     * @param integer $seati
     * @return int
     */
    private function visibleSeatCount(string $needle, int $linei, int $seati): int
    {
        $visibles = [];

        $looking = true;
        $distance = 1;

        // up and left
        while ($looking) {
            if (! isset($this->lines[$linei - $distance][$seati - $distance])) {
                $visibles[] = null;
                $looking = false;
            } else {
                if ($this->isASeat($this->lines[$linei - $distance][$seati - $distance])) {
                    $visibles[] = $this->lines[$linei - $distance][$seati - $distance];
                    $looking = false;
                }
            }

            $distance++;
        }

        $looking = true;
        $distance = 1;

        // up
        while ($looking) {
            if (! isset($this->lines[$linei - $distance][$seati])) {
                $visibles[] = null;
                $looking = false;
            } else {
                if ($this->isASeat($this->lines[$linei - $distance][$seati])) {
                    $visibles[] = $this->lines[$linei - $distance][$seati];
                    $looking = false;
                }
            }

            $distance++;
        }

        $looking = true;
        $distance = 1;

        // up and right
        while ($looking) {
            if (! isset($this->lines[$linei - $distance][$seati + $distance])) {
                $visibles[] = null;
                $looking = false;
            } else {
                if ($this->isASeat($this->lines[$linei - $distance][$seati + $distance])) {
                    $visibles[] = $this->lines[$linei - $distance][$seati + $distance];
                    $looking = false;
                }
            }

            $distance++;
        }

        $looking = true;
        $distance = 1;

        // left
        while ($looking) {
            if (! isset($this->lines[$linei][$seati - $distance])) {
                $visibles[] = null;
                $looking = false;
            } else {
                if ($this->isASeat($this->lines[$linei][$seati - $distance])) {
                    $visibles[] = $this->lines[$linei][$seati - $distance];
                    $looking = false;
                }
            }

            $distance++;
        }

        $looking = true;
        $distance = 1;

        // right
        while ($looking) {
            if (! isset($this->lines[$linei][$seati + $distance])) {
                $visibles[] = null;
                $looking = false;
            } else {
                if ($this->isASeat($this->lines[$linei][$seati + $distance])) {
                    $visibles[] = $this->lines[$linei][$seati + $distance];
                    $looking = false;
                }
            }

            $distance++;
        }

        $looking = true;
        $distance = 1;

        // down and left
        while ($looking) {
            if (! isset($this->lines[$linei + $distance][$seati - $distance])) {
                $visibles[] = null;
                $looking = false;
            } else {
                if ($this->isASeat($this->lines[$linei + $distance][$seati - $distance])) {
                    $visibles[] = $this->lines[$linei + $distance][$seati - $distance];
                    $looking = false;
                }
            }

            $distance++;
        }

        $looking = true;
        $distance = 1;

        // down and right
        while ($looking) {
            if (! isset($this->lines[$linei + $distance][$seati + $distance])) {
                $visibles[] = null;
                $looking = false;
            } else {
                if ($this->isASeat($this->lines[$linei + $distance][$seati + $distance])) {
                    $visibles[] = $this->lines[$linei + $distance][$seati + $distance];
                    $looking = false;
                }
            }

            $distance++;
        }

        $looking = true;
        $distance = 1;

        // down
        while ($looking) {
            if (! isset($this->lines[$linei + $distance][$seati])) {
                $visibles[] = null;
                $looking = false;
            } else {
                if ($this->isASeat($this->lines[$linei + $distance][$seati])) {
                    $visibles[] = $this->lines[$linei + $distance][$seati];
                    $looking = false;
                }
            }

            $distance++;
        }

        return collect($visibles)->filter(function($adjacent) use ($needle) {
            return $adjacent === $needle;
        })->count();
    }

    /**
     * Check if a val is a seat.
     *
     * @param string $val
     * @return boolean
     */
    private function isASeat(string $val): bool
    {
        return in_array($val, ['L', '#']);
    }


    /**
     * Get the input for the puzzle.
     *
     * @return string
     */
    private function getInput(): string
    {
        return 'LLLLLLLLLL.LLLL.LLLLLLL.LLLLL.LLLLLLLLLLLLLLLLLLLLLL.LL.LLLLLLLLLLL.LLLLLL.LL.LLLL.LLLLLL.LLLLLLL
LL.LLLLLLL.LLLLLLLLLLLL.LLLLL.LLLLLLLL.LLLLLLLLL.LLLLLL.LLLLLLLL.LLLLLLLLL.LL.LLLL.LLLLLL.LLLLLLL
LLLLLL.LLLLLLLLLLLLLLLL.LLL.L.LLLLLLLL.LLLL.LLLL.LLLLLL.LLLLLLLLLLLLLLLLLLLLLLLLLL.LLLL.L.LLLLLLL
LLLLLLLLLL.LLLL.LLLLLLLLLLLLL.LLLLLLLL.LLLLLLLLL.LLLLLLLLLLLLLLL.LLLLLLLLLLLLLLLLL.LLLLLLLLLLLLLL
LLLLLLLLLLLLLLL.LLLLLLL.LLLLL.LLLLLL.L.LLLLLLLLL.LLLLLL.LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL.LLLLLLL
LLLLLLLLLL.LLLLLLLLLLLL.LLLLLL.LLLLLLLLLLLLLLLLLLLLLLLLL.LLLLLLL.LLLLLLLLL.LLLLLLL.LLLLLL.LLLLLLL
LLLLLLLLLL.LLLL.LLLLLLL.LLLLL.LLLLLLLL.LLLLLLLLLLLLLLLLLLL.LLLLLLL.LLLLLLLLLLLLLLL.LLLLLL.LLLLLLL
LLLLLLLLLL.LLLL.LLLLLLLLLLLLL.LLLLLLLLLLLLLLLLLL.LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL.LLLLLL.LLLLLLL
L.L....L...L..LLLLLL.L.LLL..L...L.....L.L...L..L.LL.....L..L..LL...L........L..LLLLLLL.L......L..
LLLLLLLLLL.LLLLLLLL.LLL.LLLLLLLLLLLLLL.LLLLLLLLL.LLLLLLLLLLLLLLL.LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL
LLLLLLLLLLLLLLLLLLLLLLL.LLLL..LLLLLLLL.LL.LLLLLLL.LLLLLLLLLLLLLL.LL.LLLLLLLLLLLLLL.LLLLLLLLLLLLLL
LLL.LLLLLL.LLLL.LLLLLLLLLLLLL.LLLLLLLLLLLLLLLLLL.LLLLLLLLLLLLLLL.LLLLLLL.L.LLLLLLL.LLLLLLLLLLLLLL
LLLLLLLLLLLLLLL..L.LLLL.LLLLL.LLLLLLLL.LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL.LLLLLLLLLLLL
LLLLLLL.LLLLLLL.LLLLLLL.LLLLL.LLL.LLLLLLLLLLLL.LLLLLLLL.LLLLLLLL.LLLLLLLLL.LLLLLLLLLLLLLL.LLLLLLL
LLLLLLLLLL.LLLLLLLLLLLLLLLLLL.LLLLLLLL.LLLLLLLLL.LLLLLL.LLLLLLLL.LLLLLLLLL.LLLLLLL.LLLLLLLLLLLLLL
LLLLLLLLLL.LLLL.LLLLLLLLLL.LL.LLLLLLLL.LLLLLLLL...LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL.LLLLLL.LLLLLLL
LLLLLLLLLLLLL.LLLLLLLLL.LLLLL.LLLLLLL..LLLLLL.LLLLLLLLLLL.LLLLLL.LLLLLLLLL.LL.LLLLLLLLLLLLLLLLLLL
..L...L..LL.L....LL.L...LLLL......L...L....L...L.L...L..L..L...L.....L..L...................LLL.L
LLLLLLLLLLLL.LLLLLLL.LL.LLLLL.LLLLLLLL.LLLLLLLLL.LLLLL..LLLLLLLLLLLLLLLLLLLLLLLLLL.LLLLLL.LLLLLLL
LLLLLLLLLL.LLLL.LLLLLLLLLLLLL.LLLLLLLLLLLLLLLLLLLLLLLLLLL.LLLLLLLLLLLLLLLL.LL.LLLL.LLLLLL.LLLLLLL
LLLLLLLLLL.LLLL.LLLLLLLLLLLLLLLLLLLLLL.LLLLLLLLL.LLLLLLLLLLLLLLLLLLLLLLLLL.L.LLLLLLLLLLLL.LLLLLLL
LLLLLLLLLLLLLLL.LLLLLLL.LLLLL.LLLLLLLLLLLLLLLLLL.LLLLLL.L.LLLLLL.LLLLLLLLLLL.LLLL..LLLLLL.LLLLLLL
LLLLLLLLLL.LLLL.LLLLLLLLLLLLL.LLLLLLLL.LLLLLLLLLLLLLLLL.LLLLLLLL.LL.LLLLLLLLLLLLLLLLLLLLLLLLLLLLL
LL..LL.....LLL......LL.........L...L..L......L.....LL........LLLL.....L..................LL....L.
LLLLLLLLLL.LLLL.LLLLLLL.LLLLL.LLLLLLLL.LLLLLLLLL.L.LLLLLLLLLLLLL.LLLLL.LLLLLLLLLL.LLLLLLL.LLLLLLL
LLLLLLLLLLLLLLLLLLL.LLL.LLL.L.LLLLLLLLLLLLLLLLLL.LL.L.L.LLLLLLLL.LLLLLLLLLLLLLLLLLLLLLLL.LLLLLLLL
LL.LLLLLLL.LLLLLLLLLLLL.LLLLLLLLLLLLLL.LLLLLLLLL.LLLLLL.LLLLLLLL.LLLLLLLLL.LLLLLLL.LLL.LL.LLLLLL.
LLLLLLLLLL.LLLL.LLLLLLL.LLLLL.LLLLLLLLLLLLLLLLLL.LLLLLL.LLLLLLLLLLLLLLLLLL.LLLLLLL.LLLLLL.LLLLLLL
.LLLLLLLLLLLL.L.LLLLLLL.LLLLL.LLLLLLLL.LLLLLLLLL.LLLLLL.LLLLLLLL.LLLLLLLLLLLLLL.LLLLLLLLL.LLLLLLL
...L..L...L.....L....LL..L.....L.L..L...LLLL..L....LL.L....L.L.L......LL...L...L..LLLL.L..L......
LLLLLLLLLL.LLLLLLLLLLLL.LLLLL.LLLLLLLL.LLLL.LLLLLLLLLLL.LLLLLL...LLL.LLLLL.LLL.LLL.LLLLL..LLLLLLL
LLLLLLLLLL.LLLL.LLLLLLL.LLLLL.LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL.LLL.LLLLLLLLLL.LLLLLLL
LLLLLLLLLLLLLLLLLLLLLLL.LLLLL.LLLLLLLLLLLLLLLLLLLLLLL.L.LLLLLLLLLLLLLLLLLLLLLLLLLL.LLLLLL.LLLLLLL
LLLLLLLLLL.LLLL.LLLLLLLLLLLLL.LLLLLLLL.LL.LL.LLL.LLLLL..LLLLLLLL.LLLLLLLLL.LLLL.LLLLLLLLLLLLLLLLL
L.LLLLLLLLLLL.L..LLLLLL.LLLLL.LLLLLLLL..LLLLLLLLLLLLLLLLLLLLLLLL.LLLLLLLLLLLLLLLLL.LLLLLL.LLLLLLL
.L....LL....L......LLL...L..L..L.L.L.LLL.L.L........L....L..LL......L......L.L..LLLL.L.L..L......
LLLLLLLLLL.LLLLLLLLLLLL.LLLLLLLLLLLLLL.LLLLLLLLLLLLLLLL.LLLLLLLL.LLLLLLLL..LLLLLLL..LL.LLLLLLLLLL
LLLLLLLLLLLLLLLLLLLLLLL.LLLLL.LLLLLLLL.LLLL.LLLL.LLLLLL.LLLLLLLLLLLLLLLLLLL.LLLLLLLLLLLLLLLLLLLLL
LLLLLLLLLL..LLL.LLLLL.L.LL.LL.LLLLLLLLLLLLLLLLLL.LLLLLLLLLLLLLLLLLLLLLLLLL.LLLLLLL.LLLLLLLLLLLLLL
LLLLL.LLLLLLLLL.LLLLLLLLLLLLL.LLLLLLLL.LLLLLLLLL.LLLLLLLLLLLLL.L.LLLLLLLLL.LLLLL.L.LLLLLLLLLLLLLL
LLLLLL.....L......L.......L..L.LLL...L.L...LLL....L.......L.L..L...LL....LL.L...L...LLL.L...L..LL
LLLLLLLLLLLLLLLLLLLLLLL.LLLLLLLLLLLLLL.LLLLLLLLL.LLLLLL.LLLLLLLL.LLLLLLLLL.LLLLLLLLLLLLLL.LLLLLLL
LLLLLLLLLL.LLLLLLLLLLLLLLLLLLLLLLLLLLL.LLLLLLLLL.LLLLLL.LLLLLLLLLLLLLLLLLL.LLLLLLLLLLLLLL.L.LLLLL
LLLLLLLLLLLLLLL.LL.LLLL.LLLLL.LLLLLLLL.LLLLLLLLL.LLLLLL.LLLLLLLLLLLLLLLLLL.LLLLLLLLLLLLLL.LLLLLLL
LLLLLLLLLLLLLLLLLLLLLLL.LLLLL.LLLLLLLLLLLLLLLLLL.LLLLLL.LLLLLLLL.LLLLLL.LL.LLLLLLLLLLLLLLLLLLLLLL
....L..L.L.L...L.....L...LLL.....L.......L..LLL.LL...L.....L..L.L...L.L.L...LL...LL.L....LL.....L
LLLLLLLLLL.LLLL.LLLLLLL.LLLLL.LLLLLLLL.LLLLLLLLL.LLLLLL.LLLLLLLL.LLLLLLLLLLLLL.LLLLLLLLLL.LLL.LLL
LLL.LLLLLL.LLLL.LLLLLLL.LLLLLLLLLLLLLLLLLLL.LLLLLLLL.LL.LLLLLLLLLLLLLLL.LLLLLLLLLL.LLLLLL.LLLLLLL
LLLLLLLLLL.LLLL.LLLLLLLLLLLLL.LLLLLLLL.LLLLLLLLLLLLLL.L.LLLLLLLLLLL.LLLLLL.LLLLLL..LLLLLLLLLLLLLL
LL.LLLLLLLLLLLL.LLLLLLL.LLLLL.LLLLLLLLLLLLLLLLLL.LLLLLL.LL.LLLL..LLLLLLLLL.LLLLLLL.LLLLLL.LLLLLLL
LLLLLLLLLL.LLLL.LLLL.LL.LLLLLLLLLLLLLL.LLLLLLLLL.LLLLLL.LLLLLLLL.LLLLLLLLL.LLLLLLL.LLLLLL.L.LLLLL
LLL.LLLLLL.LLLL.LLLLLLL.LLLLL.LLLLLLLL.LLLLLLLLL.LL.LLLLLL..LLLL.LLLLLLLLL.LLLLLLL.LLLLLLLLL.LL.L
LLLLLLLLLL.LLLL.LLLLLLL.LLLLL.LLLLLLLLLLLLLLLLLL.LLLLLL.LLLLLLLL.LLLLLLLLL.LLLLLLLLLLLLLLLLLLLLLL
......L......LLL......L.LL...L..L.L.L..L.L.......L.L....L..LL.......L......L.....L.LLLLLL...L...L
LLLLLLLLLLLLLLL.LLLLLLL.LLLLL.LLLLLLLL.LLLLLLL.LLLLLLLLL.LLLLLLLLLLLLLLLLL.LLLLLLLLLLL..L.L.LLLLL
LLLLLLLLLLLLLLL.LLLLLLL.LLLL.LLLLLLLLL.LLLLLLLLLLLLLLLL.LLLLL.LLLLLLLLLLLLLLLLLLL.LLLLLLLLLLLLLLL
LLLLLLLLLL.LLLL.LLLLL.L.LLL.L.LLLLLLLL.LLLLLLL.LLLLLLLL.LLLLLLLLLLLLLLLLLLLLLLLLLL.LLLLLL.LLLLLLL
LLLLLLLLLL.LLLL.LL.LLLL.LLLLL.LLLLLLLL.LLLLLLLLL.LLLLLL.LLLLLLLL.LLLLLLLLL.LLLLLLL..LLLLL.LLLLLLL
LL.LLLLLLLLLLLL.LLLLLLL.LLLLL.LLLLLLLL.LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL.LLLLLLL.LLLLLLLLLLLLLL
.LLLLL.LLL.LLLLLLLLLLLL.LLLLL.LLLLLLLL.LLLLLL.LL.LLLLLLLLLLLL.LL.LLLLLLLLLLLLLLLLL.LLLLLL.LLLLLLL
LLLLLLLLLL.LLLL.LLLLLLLLLLLLL.LLLLLLLL.LLL.LL.L.LLLL.L.LLLLLLLLL.LLLLLLLLL.LLLLLLLLLLLLLL.LLLLLLL
LLLLLLLLLL.LLLLLLLLLLLL.LLLL..LLL.LLLL.LLLLLLLLL.LLLLLLLLLLLLLLL.LLLLLLLLLLLLLLLLL.LLLLLLLLLLLLLL
LLLLLLLLLLLLLLLLLLLL.LL.LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL.L.L.LLLLLLL.L.LLLLL.LLLLLL.LLLLLLL
.L....L...L..LL.....L.LL...L.L..LLL...L..L..L..L..L.LLLLL....LLLL.........L..L..L..L..L...LL....L
LLLLLLLLLL.LLLL.LLLLLLL.LLLL..LLLLLLLLLLLLLLLLL..L.LLLL.LLLLLLLL.LLLLLLLLL.LLLLLLL.LLLLLL.LLLLLLL
LLLLLLLLLLLLLLL.LLLLLLL.LLLLL.LLLLLLLL.LLLLLLL.L.LLLLLLLLLLLLLLLLL.LLLLLLL.LLLLLLL.LLLLLL.LLLLLLL
LLLLL.LLLL.LLLL.LLLLLLL.LLLLL.LLLLLLLL.LLLLLLLLL.LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL..LLLL
LLLLLLLLLL.LLLL.LLLLLLL.LLLLL.LLLLLLLL.LLLLLLLLL.LLLLLLLLLLLLLLL.LLLLLLLLLLLLLLLLLLLLLLLL.LLLLLLL
LL.LLLLLLLLLLLL.LLLLLLL.LLLLL.LLLL.L.L.LLLLLLLLL.LLLLLL.LLLLLLLLLLLLLLLLLL.LLLLLLL.LLLLLL.LLLLLLL
LLLLL.LLLL.LLLL.LLLLLLL.LLLLL.LLLLLLLLLLLLLLLLLL.LLLLLLLLL.LLLLLLLLLLLLLLL.LLLLLLL.LLLLLL.LLLLLLL
..L.L...L.L........L.......L......LLLLL......L.LLL.LL.........L.L....L..L.L.L...L..LL.L......LL..
LLLLLLLLLL.LL.L.LLLLLLL.LLLLL.LLLLLLLLLLLLLLLLLLLLLLLLL.LLLLLLLLLLLLLLLLLL.LLLLLLL.LLLLLL.LLLLLLL
LLLLLLLLLL.LLLL.LLLLLLLLL.LLL.LLLLLLLLLLLLLLLLLL.LLLLLL.LLLLLLLLLLLLLLL.LL.LLLLLLL.LLLLLL.LLLLLLL
LLL.LLLLLLLLL.L.LLLLLLL.LLLLLLLLLLLL.L.LLLLLLLL..LLLLLLLLLLLLLLL.LLLL.LLLLLLLLLL.L.LLLLLLLLLLLLL.
LLLLLLLLLLLLLLL.LLLLLLL.LLLLL.LLLLLLLLLLLLLLL.LL.LLLLLLLL.LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL
LLLLLLLLLL.LLLL.LLLLLLL.LLLLL.LLLLLLLL.LLLLLLLLL.LLLLLL.L.LLLLLL.LL.LLLLLL.LLLLLLL.LL.LLL.LLLLLLL
LLLLLLLLLLLLLLLLLLLLLLL.LL.LL.LLLLLLLL.LLLLLLLLL.LLLLLL.LLLLLLLL.LLLLLLLLL.LLLLLLLLLLLLLL.LLLLLLL
LLLLLLLLLL.LLLLLLLLLL.L.LLLLL.LLLLLLLL.LLLLLLLLL.LLL.LL.LLLLLLLL.LLLLLLLLL.LLLLLLL.LLLLLLLLLLLLLL
LLLLLLLLLLLLLLLLLLLLLLL.LLLLLLLLLLLLLLLLLLLLLLLL.LLLLLL.LL.LLLLL.LLLLLLLLL.LLLLLLL.LLLLLLLLLLLLLL
LLLLLLLL.L.LLLL.LLLLLLL.LLLL..LLLLLLLLLLLLLLLLLL.LLLLLLLLLLLLLLL.LLLLLLLLL.LLLLLLL.LLLLLL.LLLL.LL
.....LL.L...L..L.L..LL.L.......L...L...LLL.L.L..L.LL.L...LL..L.LL.LLL......LL.L..L.LL......L.....
LLL.LLLLLL.LLLLLLLLLLLL.LLLLLL..LLLLLL.LLLLLLLLL.LLLLLL.LLLLLLLL.LLLLLLLLL.LLLLLLLLLL.LLLLLLLL..L
LLLLLLLLLL.LLLL.LLLLL.L.LLLLL.LLLLLLLL.LLLLLLLLLLLLLLLL.LLLLLLLL.L..LLLLLL.LLLLLLL.LLLLL.LLLLLLLL
LLLLLLLLLL.LLL..LLLLLLL.LLLLL.LLLLLLLL.LLLLLLL.LLLLLLLL.LLLLLLLL.LLLLLLLLLLLLLLLLL.LLLLLL.LLLLLLL
LLLLLLLLLL.LLLLLLLLLLLL.LLLLL.LLLLLLLL.LLLLLLLLLLLLLLL..LLLLLL.LLLLLLLLL.L.LLLLL.LLLLLLLL.LLLLLLL
LLLLLLLLLL.LLLL.LLLLLLL.LLLLL.LLL.LLLLLLLLLLLLLLLLLLLLLLLLLLLLLL.LLLLLLLLL.LLLLLLL.LLLLLL.LLLLLLL
LLLLLLLLLLLLLLL.LLLLLLLLLLLLL.LLLLLLLL.LLLLLLLLL.LLLLLLLLLLLL.LL.LLL.LLLLLLLLL.LLLLLLLLLL.LLLLLLL
LLLLLLLLLLLLLLL.LLLLLLLLLLLLLLL.LLLLLL.LLLLLLLLL.LLLLLL.LLLLLLLL.LLLLLLLLLLLLLLLLL.LLLLLL.LLLLLLL
LL.LLLLLLLLLLLL.LLLLLLL.LLLL..LLLLLLL..LLLLLLLLL.LLLLLLLLLLLLLLL.LLLLLLLLL.LLLLLLLLLLLLLL.LLLLLLL
LLLLLLLL.L.LLLLLLLLLLLL.LLLLLLLLLLLLL..LLLLLLLLL.LLLLLL.LLLLLLLLLLLLLLLLLL.LLLLLLL.LLLLLL.LLLLLLL
LLLLLLLL.LLLLLLLLLLLLLL.LLLLL.LLLLLLLLLL.LLLLLLL.LLLLLL.LLLLLL.L.LLLLLLLLL.LLLLLLL.LL.LLL.LLLLLLL
LLLLLLLLLLLLLLLLLLLLLLLLLLLLL.LLLLLLLLLLLLLLLLLL.LLLLLL.LLLLLLLL.LLLLLLLLLLLLLLLLL.LLLLLL.LLLLLLL
LLLLLLLLLLLLLLL.LLLLLLL.LLLLL.LLLLL.LLLLL.LLLLLL.L.LLLL.LLLLLLLL.LLLLLLLLLLLLLLLLL.LLL.LL.LLLLLL.';
    }
}
