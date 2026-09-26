<?php

return [
    /*
    | Serve dist/nabuxui.js and dist/nabuxui.css from the package through a route
    | (like Livewire serves livewire.js). Turn off after `vendor:publish
    | --tag=nabuxui-assets` if you would rather let the web server hand them out.
    */
    'serve_assets' => true,

    'asset_path' => 'nabuxui',

    /*
    | The View Transition preset for page changes (wire:navigate, Inertia
    | visits): fade, rise, slide or zoom. Written to <html data-nx-transition>.
    */
    'transition' => 'fade',
];
