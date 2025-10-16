<?php

return [
    // Temporary file upload settings for Livewire (Filament FileUpload relies on this)
    'temporary_file_upload' => [
        // Use the public disk so previews work instantly
        'disk' => env('LIVEWIRE_UPLOAD_DISK', 'public'),
        // All uploads go to storage/app/public/livewire-tmp then symlinked via /storage
        'directory' => env('LIVEWIRE_UPLOAD_DIR', 'livewire-tmp'),
        // Remove all validation to allow large files
        'rules' => [],
        // Allow longer uploads for large images (30 minutes)
        'max_upload_time' => 30,
        // Clean up temp files older than 24 hours
        'cleanup' => true,
        // Set global max size for Livewire uploads (100MB)
        'global_validation_rules' => [],
        // Middleware for upload processing
        'middleware' => null,
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Manifest Path
    |--------------------------------------------------------------------------
    |
    | This value sets the path where Livewire will store information about
    | Livewire component's assets. This manifest file is used by mix to 
    | properly version and serve Livewire assets from a CDN if needed.
    |
    */
    'manifest_path' => null,

    /*
    |--------------------------------------------------------------------------
    | Back Button Cache
    |--------------------------------------------------------------------------
    |
    | This value determines whether the back button cache will be used on pages
    | that contain Livewire. By disabling back button cache, it ensures that
    | the back button shows the correct state of components, instead of
    | potentially stale cached data.
    |
    */
    'back_button_cache' => false,

    /*
    |--------------------------------------------------------------------------
    | Render On Redirect
    |--------------------------------------------------------------------------
    |
    | This value determines whether Livewire will render before it's redirected
    | or not. Setting this to "false" (default) will mean the render method is
    | skipped when a redirect is intended, and the browser will redirect to a
    | new page where the component will be rendered fresh.
    |
    */
    'render_on_redirect' => false,
];
