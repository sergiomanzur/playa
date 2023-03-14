<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <a href="/dashboard"> {{ __('Panel Principal') }} </a>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div style="padding: 20px;" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <h2 style="font-size: x-large; border-bottom: 1px solid; margin-bottom: 15px;">
                    Recibo de Pago de Mensualidad
                </h2>
                <div class="p-6 text-gray-900 dark:text-gray-100">


                    <p style="font-size: larger"><strong>{{$data['user']['name']}}</strong> - {{$data['user']['username']}}</p>
                    <p>{{$data['manzana']['nombre']}} - {{$data['lote']['nombre']}}</p>
                    <div style="align-items: center; margin-top:35px; justify-content: center;" class="flex flex-col md:flex-col">
                        <p>Hemos recibido tu pago por la cantidad de: ${{number_format($data['pago']['cantidad'],2)}}</p>
                        <img style="width:400px;" src="{{url('/assets/img/firma.png')}}" alt="imagen de la firma"/>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

