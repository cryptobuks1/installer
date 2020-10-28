<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;
use LaravelZero\Framework\Contracts\Providers\ComposerContract;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class NewCommand extends Command
{
    /**
     * The name and signature of the command.
     *
     * @var string
     */
    protected $signature = 'new {name=laravel-zero}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Create a new Laravel Zero application';

    /**
     * Holds an instance of the composer service.
     *
     * @var \LaravelZero\Framework\Contracts\Providers\ComposerContract
     */
    protected $composer;

    /**
     * Creates a new instance of the NewCommand class.
     *
     * @param \LaravelZero\Framework\Contracts\Providers\ComposerContract $composer
     */
    public function __construct(ComposerContract $composer)
    {
        parent::__construct();

        $this->composer = $composer;
    }

    public function renameNewApp($projectName)
    {
        $process = new Process('php application app:rename ' . $projectName, $projectName);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $this->info($process->getOutput());
    }

    /**
     * Execute the command. Here goes the code.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->info('Crafting application..');

        $this->composer->createProject(
            'laravel-zero/laravel-zero',
            $this->argument('name'),
            ['--prefer-dist']
        );

        $this->renameNewApp($this->argument('name'));

        $this->comment('Application ready! Build something amazing.');
    }
}
