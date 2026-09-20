<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Hard Constraints
    |--------------------------------------------------------------------------
    |
    | Livewire Silently scaffolding is configured to allow security constraints
    |
    */

    'temporary_file_upload' => [
        'rules' => [
            'required',
            'file',
            'max:' . (10 * 1024),
        ],
        // Keep temporary uploads OUT of the public webroot. Previews still work
        // via the signed `livewire.preview-file` route.
        'disk' => 'local',
        'directory' => 'livewire-tmp',
        'middleware' => 'throttle:60,1',
        'preview_mimes' => [
            'png', 'gif', 'bmp', 'svg', 'wav', 'mp4',
            'mov', 'avi', 'wmv', 'mp3', 'm4a',
            'jpg', 'jpeg', 'mpga', 'webp', 'wma',
        ],
    ],

];