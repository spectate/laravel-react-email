<?php

namespace Spectate\ReactEmail\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class MakeReactEmailCommand extends GeneratorCommand
{
    protected $name = 'make:react-email';

    protected $description = 'Create a new ReactMailable class and React Email template';

    protected $type = 'React Email';

    protected function getStub(): string
    {
        return __DIR__.'/../../stubs/ReactMailable.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\Mail';
    }

    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the mailable class'],
        ];
    }

    protected function buildClass($name): string
    {
        $replace = $this->buildReplacements();

        return str_replace(
            array_keys($replace),
            array_values($replace),
            parent::buildClass($name)
        );
    }

    protected function buildReplacements(): array
    {
        $name = Str::kebab($this->argument('name'));

        return [
            '{{ view }}' => "react-email::$name",
            '{{ text }}' => "react-email::$name-text",
        ];
    }

    public function handle(): bool
    {
        if (parent::handle() === false) {
            return false;
        }

        return $this->createReactEmailTemplate();
    }

    protected function createReactEmailTemplate(): bool
    {
        $templatePath = $this->getTemplatePath();

        if (File::exists($templatePath)) {
            $this->error('React Email template already exists!');

            return false;
        }

        $this->ensureTemplateDirectoryExists();
        $this->createTemplateFromStub($templatePath);

        $this->components->info(sprintf('React email template [%s] created successfully.', $templatePath));

        return true;
    }

    protected function getTemplatePath(): string
    {
        $name = Str::kebab($this->argument('name'));
        $templatesPath = config('react-email.email_templates_path', resource_path('views/react-emails'));

        return $templatesPath.'/'.$name.'.tsx';
    }

    protected function ensureTemplateDirectoryExists(): void
    {
        $templatesPath = dirname($this->getTemplatePath());
        File::makeDirectory($templatesPath, 0755, true, true);
    }

    protected function createTemplateFromStub(string $templatePath): void
    {
        $stubPath = __DIR__.'/../../stubs/react-email-template.stub';
        $stub = File::get($stubPath);

        $content = str_replace(
            ['{{ functionName }}', '{{ name }}'],
            [Str::studly($this->argument('name')).'Email', Str::studly($this->argument('name'))],
            $stub
        );

        File::put($templatePath, $content);
    }
}
