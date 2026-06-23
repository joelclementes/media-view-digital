<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
        <div class="w-full max-w-md rounded-xl bg-white p-8 shadow-md text-center">
            {{-- <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-primary-100">
                <x-lucide-construction class="h-10 w-10 text-primary-700" />
            </div> --}}
            <div class="mb-6 flex justify-center">
                <img src="{{ asset('assets/images/construction-workers.svg') }}" alt="Sitio en construcción"
                    class="w-64 h-auto">
            </div>

            <h1 class="text-2xl font-bold text-gray-800">
                Sitio en construcción
            </h1>

            <p class="mt-3 text-sm text-gray-600">
                Por ahora el acceso al sistema está en construcción.
            </p>

            <form method="POST" action="{{ route('logout') }}" class="mt-6">
                @csrf
                <button type="submit"
                    class="w-full rounded-md bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700">
                    Cerrar sesión
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
