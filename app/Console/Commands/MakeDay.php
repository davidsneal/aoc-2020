<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\File;

class MakeDay extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:day {day} {--title=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new class for a given day.';

    /**
     *
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return  storage_path('stubs/day.stub');
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (class_exists('\App\Advent\Days\Day'.$this->argument('day'))) return $this->error(' A class for that day '.$this->argument('day').' already exists. ');

        $stub = $this->buildClass('\App\Advent\Days\Day'.$this->argument('day'));
        $stub = str_replace('{{ day }}', $this->argument('day'), $stub);
        $stub = str_replace('{{ title }}', $this->option('title'), $stub);

        File::put(base_path('app/Advent/Days/Day'.$this->argument('day').'.php'), $stub);
    }
}
