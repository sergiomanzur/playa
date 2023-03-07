<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Panel Principal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div style="display: flex;">
                        <div style="width: 70%;">
                            <h2 style="font-size: x-large">Bienvenido a tu cuenta de Playa Hermosa</h2>
                            <h3>{{$data['user']['name']}}</h3>
                            <p>{{$data['manzana']['nombre']}} - {{$data['lote']['nombre']}}</p>
                            <div id="piechart" style="margin-top: 20px; width: 90%; height: 300px;"></div>
                        </div>
                        <div style="width: 30%; padding: 15px; margin-top: 30px;">

                            <h3>Balance a crédito</h3>
                            <p>${{number_format($data['balance_a_credito'],2)}}</p>

                            <br/>

                            <h3>Balance de pagos realizados</h3>
                            <p>${{number_format($data['balance_de_pagos_realizados'],2)}}</p>

                            <br/>

                            <h3>Balance pendiente por pagar</h3>
                            <p>${{number_format($data['balance_pendiente_por_pagar'],2)}}</p>

                        </div>
                    </div>

                    <!-- INICIA  SCRIPT DE PIE CHART -->
                    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                    <script type="text/javascript">
                        google.charts.load('current', {'packages':['corechart']});
                        google.charts.setOnLoadCallback(drawChart);

                        function drawChart() {

                            var data = google.visualization.arrayToDataTable([
                                ['Concepto', 'Cantidad'],
                                ['Pagado',     {{$data['balance_pagado']}}],
                                ['Por Pagar', {{$data['balance_pendiente_por_pagar']}}],
                            ]);

                            var options = {
                                title: 'Porcentajes',
                                backgroundColor: 'white',
                                legend: {position: 'top', textStyle: {color: 'blue', fontSize: 16}},
                                titleTextStyle: {color: 'blue'}
                            };

                            var chart = new google.visualization.PieChart(document.getElementById('piechart'));

                            chart.draw(data, options);
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

