<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use League\Csv\Reader;
use League\Csv\Statement;

class Advent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'advent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the advent of code problems.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->day1();
    }

    /**
     * Report Repair
     * https://adventofcode.com/2020/day/1
     *
     * @return void
     */
    private function day1()
    {
        $reader = Reader::createFromPath(storage_path('app/day1.csv'), 'r');
        $records = collect(Statement::create()->process($reader))->flatten();

        foreach ($records as $record) {
            $match = $records->filter(function($r) use ($record) {
                return $r + $record === 2020;
            });

            if ($match->first()) {
                $this->info('Day 1: Report Repair');
                $this->table(['#1', '#2', 'Answer'], [
                    [$record, $match->first(), $record * $match->first()]
                ]);
                break;
            }
        }

        $solved = false;

        foreach ($records as $level1) {
            if ($solved) break;

            foreach ($records as $level2) {
                if ($solved) break;

                foreach ($records as $level3) {
                    if ($solved) break;

                    if ($level1 + $level2 + $level3 === 2020) {
                        $this->table(['#1', '#2', '#3', 'Answer'], [
                            [$level1, $level2, $level3, $level1 * $level2 * $level3]
                        ]);
                        $solved = true;
                    };
                }
            }
        }
    }
}
