<?php

namespace Spectate\ReactEmail\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use RuntimeException;

class ReactEmailBuilder
{
    protected string $rendererScriptPath;

    protected string $templatePath;

    protected string $outputPath;

    public function __construct()
    {
        $this->rendererScriptPath = __DIR__.'/../../scripts/react-email-renderer.tsx';

        $this->templatePath = config('react-email.email_templates_path');
        $this->outputPath = config('react-email.blade_output_path');
    }

    public function getTemplatePath(): string
    {
        return $this->templatePath;
    }

    /**
     * @return array [string, string]
     */
    public function buildTemplate(string $filenameWithoutExtension): array
    {
        $this->ensureDirectoriesExist();

        $htmlOutputPath = "$this->outputPath/$filenameWithoutExtension.blade.php";
        $plainTextOutputPath = "$this->outputPath/$filenameWithoutExtension-text.blade.php";

        $templateFilePath = "$this->templatePath/$filenameWithoutExtension.tsx";

        $process = Process::run("npx --yes tsx $this->rendererScriptPath \"$templateFilePath\"");

        if (! $process->successful()) {
            throw new RuntimeException("Failed to build template: $templateFilePath\n".$process->errorOutput());
        }

        $output = json_decode($process->output(), true);

        File::put($htmlOutputPath, $output['html']);
        File::put($plainTextOutputPath, $output['plainText']);

        return [$htmlOutputPath, $plainTextOutputPath];
    }

    private function ensureDirectoriesExist(): void
    {
        // Ensure the template directory exists
        File::makeDirectory($this->templatePath, 0755, true, true);

        // Ensure the output directory exists
        File::makeDirectory($this->outputPath, 0755, true, true);
    }
}
