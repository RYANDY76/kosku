<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('about-kosku', function () {
    $this->info('KosKu Laravel Project - Sistem informasi pencarian kos di Palu.');
});
