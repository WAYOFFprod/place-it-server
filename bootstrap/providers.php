<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\FortifyServiceProvider::class,
    ...app()->environment('local', 'testing') ? [App\Providers\DuskServiceProvider::class] : [],
];
