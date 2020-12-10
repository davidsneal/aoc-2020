<?php

namespace App\Advent\Days;

use App\Advent\Day;

class Day10 {
    use Day;

    public $title = 'Day 10: Adapter Array';

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
        sort($lines);

        $current = 0;
        $ones = [];
        $threes = [];

        foreach ($lines as $line) {
            if ((int) $line === ($current + 1)) {
                $ones[] = $line;
            } else if ((int) $line === ($current + 3)) {
                $threes[] = $line;
            }

            $current = $line;
        }

        $threes[] = ($current + 3);

        $result = count($ones) * count($threes);

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

        $lines = explode("\n", $this->getInput());
        $lines[] = 0;
        sort($lines);

        $variations = [1];

        for ($i = 0; $i < count($lines); $i++) {
            for ($j = 0; $j < $i; $j++) {
                if ($lines[$i] - $lines[$j] <= 3) {
                    @$variations[$i] += @$variations[$j];
                }
            }
        }

        $result = $variations[count($lines) - 1];

        $this->finish();

        $this->addPart([
            'table' => [
                'headings' => ['Part 2: Answer'],
                'data' => [[$result]],
            ],
            'time' => $this->getTime(),
        ]);
    }

    private function addVariation()
    {

    }

    /**
     * Get the input for the puzzle.
     *
     * @return string
     */
    private function getInput(): string
    {
        return '144
10
75
3
36
80
143
59
111
133
1
112
23
62
101
137
41
24
8
121
35
105
161
69
52
21
55
29
135
142
38
108
141
115
68
7
98
82
9
72
118
27
153
140
61
90
158
102
28
134
91
2
17
81
31
15
120
20
34
56
4
44
74
14
147
11
49
128
16
99
66
47
125
155
130
37
67
54
60
48
136
89
119
154
122
129
163
73
100
85
95
30
76
162
22
79
88
150
53
63
92';
    }
}
