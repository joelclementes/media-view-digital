<div>
    <button wire:click="toggle"
        class="relative inline-flex h-6 w-11 items-center rounded-full transition
    {{ $user->activo ? 'bg-green-600' : 'bg-gray-300' }}">
        <span
            class="inline-block h-4 w-4 transform rounded-full bg-white transition
        {{ $user->activo ? 'translate-x-6' : 'translate-x-1' }}">
        </span>
    </button>
</div>
