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
        $start = microtime(true);

        $this->day1();
        $this->day2();

        $finish = (microtime(true) - $start) * 1000;
        $this->line('All days took: '.$finish.'ms');
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

    /**
     * Password Philosophy
     * https://adventofcode.com/2020/day/2
     *
     * @return void
     */
    private function day2()
    {
        $this->info('Day 2: Password Philosophy');

        $start = microtime(true);

        $csv = array_map('str_getcsv', file(storage_path('app/day2.csv')));
        $validPasswords = collect($csv)->filter(function($line) {
            $parts = explode(':', $line[0]);
            $password = trim($parts[1]);
            $parts = explode(' ', $parts[0]);
            $letter = trim($parts[1]);
            $parts = explode('-', $parts[0]);

            $occurrences = substr_count($password, $letter);

            return $occurrences >= $parts[0] && $occurrences <= $parts[1];
        });

        $time = (microtime(true) - $start) * 1000;
        $this->table(['Part 1: Answer'], [
            [$validPasswords->count()]
        ]);
        $this->line('took: '.$time.'ms');

        $start = microtime(true);

        $validPasswords = collect($csv)->filter(function($line) {
            $parts = explode(':', $line[0]);
            $password = trim($parts[1]);
            $parts = explode(' ', $parts[0]);
            $letter = trim($parts[1]);
            $parts = explode('-', $parts[0]);

            $pos1 = substr($password, $parts[0] - 1, 1) == $letter;
            $pos2 = substr($password, $parts[1] - 1, 1) == $letter;

            if ($pos1 && $pos2) return false;

            return $pos1 || $pos2;
        });

        $time = (microtime(true) - $start) * 1000;
        $this->table(['Part 2: Answer'], [
            [$validPasswords->count()]
        ]);
        $this->line('took: '.$time.'ms');
    }
}
