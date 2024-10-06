<?php

namespace Spectate\ReactEmail\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Spectate\ReactEmail\Services\ReactEmailBuilder;

class BuildReactEmailsCommand extends Command
{
    protected $signature = 'react-email:build';

    protected $description = 'Build React Email templates to HTML';

    protected ReactEmailBuilder $builder;

    public function __construct(ReactEmailBuilder $builder)
    {
        parent::__construct();
        $this->builder = $builder;
    }

    public function handle(): void
    {
        $this->info('Building React Email templates...');

        $templates = $this->getTemplates();

        if (empty($templates)) {
            $this->info('The email templates directory is empty. Nothing to build.');

            return;
        }

        $results = $this->buildTemplates($templates);

        $this->displayResults($results);
    }

    protected function getTemplates(): array
    {
        return File::files($this->builder->getTemplatePath());
    }

    protected function buildTemplates(array $templates): array
    {
        $built = [];
        $errors = [];

        $this->output->progressStart(count($templates));

        foreach ($templates as $template) {
            if ($this->isValidTemplate($template)) {
                $result = $this->buildTemplate($template);
                $result ? $built[] = $result : $errors[] = $result;
            }
            $this->output->progressAdvance();
        }

        $this->output->progressFinish();

        return compact('built', 'errors');
    }

    protected function isValidTemplate($template): bool
    {
        return in_array($template->getExtension(), ['tsx', 'ts', 'jsx', 'js']);
    }

    protected function buildTemplate($template): array|string
    {
        try {
            return $this->builder->buildTemplate($template->getFilenameWithoutExtension());
        } catch (Exception $e) {
            return "Failed to build {$template->getFilename()}: {$e->getMessage()}";
        }
    }

    protected function displayResults(array $results): void
    {
        if (! empty($results['built'])) {
            $this->info('Successfully built templates:');
            foreach ($results['built'] as $file) {
                $this->line("- $file[0]");
            }
        }

        if (! empty($results['errors'])) {
            $this->error("\nEncountered errors while building some templates:");
            foreach ($results['errors'] as $error) {
                $this->error("- $error");
            }
        }
    }
}
