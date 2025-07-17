<?php

use function Laravel\Folio\name;

name('events.show');
?>

<x-layouts.admin>

    <x-slot name="header">
        <h2 class="text-lg font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Event Detail: ') . $event->title }}
        </h2>
    </x-slot>

 

</x-layouts.admin>