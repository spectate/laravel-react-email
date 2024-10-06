<?php

namespace Spectate\ReactEmail\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class ReactEmailDevServerCommand extends Command
{
    protected $name = 'react-email:dev';

    protected $description = 'Run the React Email dev server';

    public function handle(): bool
    {
        $templatesPath = config('react-email.email_templates_path', resource_path('views/react-emails'));

        File::makeDirectory($templatesPath, 0755, true, true);

        $this->info('Starting React Email dev server...');

        $process = new Process(['npx', '--yes', 'tsx', './node_modules/react-email/dist/cli/index.js', 'dev', '--dir', $templatesPath]);
        $process->setTty(true);
        $process->setTimeout(null);

        $process->run(function ($type, $buffer) {
            $this->output->write($buffer);
        });

        return true;
    }
}
