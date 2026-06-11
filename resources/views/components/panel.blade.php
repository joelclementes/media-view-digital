<div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8 p-6 lg:p-8">
    @can('subir_reportes')
        <a href="#"
            class="block max-w-sm rounded-md
          transition duration-300 ease-in-out
          hover:-translate-y-1">

            <div
                class="border border-accent-500 border-default rounded-md shadow-xs bg-primary-200
               hover:shadow-lg hover:shadow-secondary-400">

                <div class="flex justify-center pt-6">
                    <img class="rounded-t-base w-auto h-24" src="{{ asset('assets/images/reportes-subir.svg') }}"
                        alt="">
                </div>

                <div class="p-6 text-center">
                    <h5 class="mt-3 mb-6 text-1xl font-semibold tracking-tight text-heading">
                        Subir reportes
                    </h5>
                </div>

            </div>
        </a>
    @endcan
    @can('ver_reportes')
        <a href="#"
            class="block max-w-sm rounded-md
          transition duration-300 ease-in-out
          hover:-translate-y-1">

            <div
                class="border border-accent-500 border-default rounded-md shadow-xs bg-primary-200
               hover:shadow-lg hover:shadow-secondary-400">

                <div class="flex justify-center pt-6">
                    <img class="rounded-t-base w-auto h-24" src="{{ asset('assets/images/reportes-ver.svg') }}"
                        alt="">
                </div>

                <div class="p-6 text-center">
                    <h5 class="mt-3 mb-6 text-1xl font-semibold tracking-tight text-heading">
                        Reportes
                    </h5>
                </div>

            </div>
        </a>
    @endcan
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8 p-6 lg:p-8">
    @can('ver_medios_electronicos')
        <a href="{{ route('m-electronicos-index') }}"
            class="block max-w-sm rounded-md
          transition duration-300 ease-in-out
          hover:-translate-y-1">

            <div
                class="border border-accent-500 border-default rounded-md shadow-xs bg-primary-200
               hover:shadow-lg hover:shadow-secondary-400">

                <div class="flex justify-center pt-6">
                    <img class="rounded-t-base w-auto h-24" src="{{ asset('assets/images/m-electronicos-2.svg') }}"
                        alt="">
                </div>

                <div class="p-6 text-center">
                    <h5 class="mt-3 mb-6 text-1xl font-semibold tracking-tight text-heading">
                        Medios electrónicos
                    </h5>
                </div>

            </div>
        </a>
    @endcan

    @can('ver_medios_impresos')
        <a href="{{ route('m-impresos-index') }}"
            class="block max-w-sm rounded-md
          transition duration-300 ease-in-out
          hover:-translate-y-1">

            <div
                class="border border-accent-500 border-default rounded-md shadow-xs bg-primary-200
               hover:shadow-lg hover:shadow-secondary-400">

                <div class="flex justify-center pt-6">
                    <img class="rounded-t-base w-auto h-24" src="{{ asset('assets/images/m-impresos-2.svg') }}"
                        alt="">
                </div>

                <div class="p-6 text-center">
                    <h5 class="mt-3 mb-6 text-1xl font-semibold tracking-tight text-heading">
                        Medios impresos
                    </h5>
                </div>

            </div>
        </a>
    @endcan

    @can('ver_radio')
        <a href="{{ route('m-radio-index') }}"
            class="block max-w-sm rounded-md
          transition duration-300 ease-in-out
          hover:-translate-y-1">

            <div
                class="border border-accent-500 border-default rounded-md shadow-xs bg-primary-200
               hover:shadow-lg hover:shadow-secondary-400">

                <div class="flex justify-center pt-6">
                    <img class="rounded-t-base w-auto h-24" src="{{ asset('assets/images/m-radio-2.svg') }}" alt="">
                </div>

                <div class="p-6 text-center">
                    <h5 class="mt-3 mb-6 text-1xl font-semibold tracking-tight text-heading">
                        Radio
                    </h5>
                </div>
            </div>
        </a>
    @endcan

    @can('ver_television')
        <a href="#"
            class="block max-w-sm rounded-md
          transition duration-300 ease-in-out
          hover:-translate-y-1">

            <div
                class="border border-accent-500 border-default rounded-md shadow-xs bg-primary-200
               hover:shadow-lg hover:shadow-secondary-400">

                <div class="flex justify-center pt-6">
                    <img class="rounded-t-base w-auto h-24" src="{{ asset('assets/images/m-television.svg') }}"
                        alt="">
                </div>

                <div class="p-6 text-center">
                    <h5 class="mt-3 mb-6 text-1xl font-semibold tracking-tight text-heading">
                        Televisión
                    </h5>
                </div>

            </div>
        </a>
    @endcan

    @can('ver_soportes_promocionales')
        <a href="{{ route('m-soportes-promocionales-index') }}"
            class="block max-w-sm rounded-md
          transition duration-300 ease-in-out
          hover:-translate-y-1">

            <div
                class="border border-accent-500 border-default rounded-md shadow-xs bg-primary-200
               hover:shadow-lg hover:shadow-secondary-400">

                <div class="flex justify-center pt-6">
                    <img class="rounded-t-base w-auto h-24" src="{{ asset('assets/images/m-soportes-promocionales.svg') }}"
                        alt="">
                </div>

                <div class="p-6 text-center">
                    <h5 class="mt-3 mb-6 text-1xl font-semibold tracking-tight text-heading">
                        Soportes promocionales
                    </h5>
                </div>

            </div>
        </a>
    @endcan

    @can('ver_moviles')
        <a href="{{ route('m-propaganda-movil-index') }}"
            class="block max-w-sm rounded-md
          transition duration-300 ease-in-out
          hover:-translate-y-1">

            <div
                class="border border-accent-500 border-default rounded-md shadow-xs bg-primary-200
               hover:shadow-lg hover:shadow-secondary-400">

                <div class="flex justify-center pt-6">
                    <img class="rounded-t-base w-auto h-24" src="{{ asset('assets/images/m-propaganda-movil-2.svg') }}"
                        alt="">
                </div>

                <div class="p-6 text-center">
                    <h5 class="mt-3 mb-6 text-1xl font-semibold tracking-tight text-heading">
                        Propaganda móvil
                    </h5>
                </div>

            </div>
        </a>
    @endcan

    @can('ver_cine')
        <a href="#"
            class="block max-w-sm rounded-md
          transition duration-300 ease-in-out
          hover:-translate-y-1">

            <div
                class="border border-accent-500 border-default rounded-md shadow-xs bg-primary-200
               hover:shadow-lg hover:shadow-secondary-400">

                <div class="flex justify-center pt-6">
                    <img class="rounded-t-base w-auto h-24" src="{{ asset('assets/images/m-cine.svg') }}" alt="">
                </div>

                <div class="p-6 text-center">
                    <h5 class="mt-3 mb-6 text-1xl font-semibold tracking-tight text-heading">
                        Cine
                    </h5>
                </div>

            </div>
        </a>
    @endcan

</div>
