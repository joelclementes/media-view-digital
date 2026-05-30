@props(['section', 'title' => null])
@php
    switch ($section) {
        case 'sujeto':
            $class = 'bg-slate-100 text-slate-900';
            break;
        case 'medio':
            $class = 'bg-gray-100 text-gray-900';
            break;
        case 'publicación':
            $class = 'bg-zinc-100 text-zinc-900';
            break;
        case 'autor':
            $class = 'bg-neutral-100 text-neutral-900';
            break;
        case 'referencia':
            $class = 'bg-stone-100 text-stone-900';
            break;
        case 'observacion':
            $class = 'bg-slate-100 text-slate-900';
            break;
        case 'archivos':
            $class = 'bg-stone-100 text-stone-900';
            break;
        default:
            $class = 'bg-slate-100 text-slate-900';
            break;
    }
@endphp

<section class="{{ $class }} p-4 rounded-md mb-5 shadow-md">
    <h3 class="font-semibold text-lg mb-2">{{ $title }}</h3>
    {{ $slot }}
</section>
