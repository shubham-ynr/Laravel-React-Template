<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

if (!function_exists('studlyComponentName')) {
    function studlyComponentName($name)
    {
        // Replace - and _ with spaces, capitalize each word, remove spaces
        return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $name)));
    }
}

function createReactFile($command, $type, $baseDir, $name, $ext, $template)
{
    // Normalize slashes
    $relativePath = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $name);

    // Extract filename without extension
    $nameWithoutExt = pathinfo($relativePath, PATHINFO_FILENAME);

    // Make sure the React component name is valid PascalCase
    $componentName = studlyComponentName($nameWithoutExt);

    // Target directory
    $dirPath = resource_path("js/{$baseDir}/" . dirname($relativePath));
    if ($dirPath === resource_path("js/{$baseDir}/.")) {
        $dirPath = resource_path("js/{$baseDir}");
    }

    // Ensure directory exists
    File::ensureDirectoryExists($dirPath);

    // Full file path
    $filePath = $dirPath . DIRECTORY_SEPARATOR . $nameWithoutExt . $ext;

    // Check if file exists and confirm overwrite
    if (File::exists($filePath)) {
        if (!$command->confirm("❌ {$type} already exists at {$filePath}. Overwrite?")) {
            return;
        }
        File::delete($filePath);
    }

    // Replace {{NAME}} in template with proper component name
    $content = str_replace('{{NAME}}', $componentName, $template);

    // Write the file
    File::put($filePath, $content);

    // Show relative path from Laravel root
    $relativePathToShow = Str::after($filePath, base_path() . DIRECTORY_SEPARATOR);
    $command->info("✅ {$type} created at: {$relativePathToShow}");
}

// Create a new React Page
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

// Create a new React Component
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
    createReactFile($this, 'Component', 'components', $name, '.jsx', $template);
})->purpose('Create a new React Component');

// Create a new React Utility function
Artisan::command('react:utils {name}', function ($name) {
    $template = <<<JS
export function {{NAME}}() {
    // TODO: implement utility logic
}
JS;
    createReactFile($this, 'Utility', 'utils', $name, '.js', $template);
})->purpose('Create a new React Utility file');
