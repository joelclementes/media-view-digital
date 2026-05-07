@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-primary-300 focus:border-primary-900 focus:border-500 focus:ring-500 rounded-md shadow-sm']) !!}>
