<?php

namespace Spectate\ReactEmail;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Str;

class ReactMailable extends Mailable
{
    protected function buildView(): array|string
    {
        if (config('react-email.enable_hot_reload')) {
            $this->renderHot();
        }

        return parent::buildView();
    }

    private function renderHot(): void
    {
        $templateName = Str::kebab(class_basename($this));

        (new Services\ReactEmailBuilder)
            ->buildTemplate($templateName);
    }
}
