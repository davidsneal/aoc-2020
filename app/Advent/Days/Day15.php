<?php

namespace App\Advent\Days;

use App\Advent\Day;

class Day15 {
    use Day;

    public $title = 'Day 15: Rambunctious Recitation';

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

        $bits = str_getcsv($this->getInput());
        $numbers = [];

        foreach ($bits as $i => $number) {
            $numbers[$i + 1] = (int) $number;
        }

        $i = count($numbers);
        $last = $numbers[$i];

        while ($i < 2020) {
            $occurences = array_keys($numbers, $last);

            if (count($occurences) == 1) {
                $numbers[] = $last = 0;
            } else {
                rsort($occurences);
                $numbers[] = $last = $occurences[0] - $occurences[1];

                if (count($occurences) == 3) unset($numbers[$occurences[2]]);
            }

            $i++;
        }

        $result = $numbers[$i];

        $this->finish();

        $this->addPart([
            'table' => [
                'headings' => ['Part 1: Answer'],
                'data' => [[$result]],
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

        $bits = str_getcsv($this->getInput());
        $numbers = [];

        foreach ($bits as $i => $number) {
            $numbers[$i + 1] = (int) $number;
        }

        $i = count($numbers);
        $last = $numbers[$i];

        while ($i < 30000000) {
            $occurences = array_keys($numbers, $last);

            if (count($occurences) == 1) {
                $numbers[] = $last = 0;
            } else {
                rsort($occurences);
                $numbers[] = $last = $occurences[0] - $occurences[1];

                if (count($occurences) == 3) unset($numbers[$occurences[2]]);
            }

            if ($i % 100000 == 0) echo number_format($i)."\n";

            $i++;
        }

        $result = $numbers[$i];

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
     * Get the input for the puzzle.
     *
     * @return string
     */
    private function getInput(): string
    {
        return '6,3,15,13,1,0';
    }
}
