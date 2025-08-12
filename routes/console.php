<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

function createReactFile($command, $type, $baseDir, $name, $ext, $template)
{
    $relativePath = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $name);
    $nameWithoutExt = pathinfo($relativePath, PATHINFO_FILENAME);
    $dirPath = resource_path("js/{$baseDir}/" . dirname($relativePath));
    if ($dirPath === resource_path("js/{$baseDir}/.")) {
        $dirPath = resource_path("js/{$baseDir}");
    }
    File::ensureDirectoryExists($dirPath);
    $filePath = $dirPath . DIRECTORY_SEPARATOR . $nameWithoutExt . $ext;
    if (File::exists($filePath)) {
        if (!$command->confirm("❌ {$type} already exists at {$filePath}. Overwrite?")) {
            return;
        }
        File::delete($filePath);
    }
    $content = str_replace('{{NAME}}', $nameWithoutExt, $template);
    File::put($filePath, $content);
    $command->info("✅ {$type} created at: {$filePath}");
}

Artisan::command('react:page {name}', function ($name) {
    $template = <<<JSX
import React from 'react';
export default function {{NAME}}() {
    return (
        <div>
            <h1>{{NAME}} Page</h1>
        </div>
    );
}
JSX;
    createReactFile($this, 'Page', 'Pages', $name, '.jsx', $template);
})->purpose('Create a new React Page');

Artisan::command('react:component {name}', function ($name) {
    $template = <<<JSX
import React from 'react';
export default function {{NAME}}() {
    return (
        <div>
            {{NAME}} Component
        </div>
    );
}
JSX;
    createReactFile($this, 'Component', 'Components', $name, '.jsx', $template);
})->purpose('Create a new React Component');

Artisan::command('react:utils {name}', function ($name) {
    $template = <<<JS
export function {{NAME}}() {
    // TODO: implement utility logic
}
JS;
    createReactFile($this, 'Utility', 'utils', $name, '.js', $template);
})->purpose('Create a new React Utility file');
