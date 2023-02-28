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
                            Bienvenido a tu cuenta de Playa Hermosa, {{$data['user']['name']}}
                            <div id="piechart" style="width: 100%; height: 500px;"></div>
                        </div>
                        <div style="width: 30%;">

                        </div>
                    </div>

                    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                    <script type="text/javascript">
                        google.charts.load('current', {'packages':['corechart']});
                        google.charts.setOnLoadCallback(drawChart);

                        function drawChart() {

                            var data = google.visualization.arrayToDataTable([
                                ['Concepto', 'Cantidad'],
                                ['Pagado',     190000],
                                ['Por Pagar',      110000],
                            ]);

                            var options = {
                                title: 'Porcentaje Pagado',
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

