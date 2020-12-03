<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Advent\Days;

class Advent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'advent {day}';

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
        if (! class_exists('\App\Advent\Days\Day'.$this->argument('day'))) return $this->error(' Day '.$this->argument('day').' hasn’t been completed. ');

        $class = '\App\Advent\Days\Day'.$this->argument('day');
        $day = (new $class)();

        $this->info($day->title);

        foreach ($day->getParts() as $part) {
            $this->table($part['table']['headings'], $part['table']['data']);
            $this->line('took: '.$part['time'].'ms');
        }
    }
}
