<?php

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;

Route::get('/tiptap-editor/blocks/{block}', function (string $block): ?string {
    return json_encode(Blade::render($block));
});