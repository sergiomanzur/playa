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
                    <div class="flex flex-col md:flex-row">
                        <div  class="md:w-3/4 p-4">
                            <h2 style="font-size: x-large">Bienvenido a tu cuenta de Playa Hermosa</h2>
                            <p>{{$data['user']['name']}}</p>
                            <p>{{$data['manzana']['nombre']}} - {{$data['lote']['nombre']}}</p>
{{--                            <div id="piechart" style="margin-top: 20px; width: 90%; height: 300px;"></div>--}}
                            <div style="width:100%; margin: auto; text-align: center; margin-top: 15px;">
                                <h2 style="margin-bottom: 15px;font-size: x-large; font-weight: bolder">
                                    PORCENTAJE POR PAGAR: {{$data['porcentaje_por_pagar']}}%</h2>
                                <div class="chart-container">
                                    <h2 class="chart-label"></h2>
                                    <canvas id="car-chart" style="max-width:100%;max-height: 300px;">
                                    </canvas>
                                </div>
                                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                            </div>
                            <div style="margin:auto; margin-top: 20px; padding-right: 25%; padding-left: 25%">
                                <table>
                                    <tr>
                                        <td>Pago Promesa</td>
                                        <td>${{number_format($data['promesa'],2)}}</td>
                                    </tr>
                                    <tr>
                                        <td>Pago a Meses</td>
                                        <td>${{number_format($data['credito'],2)}}</td>
                                    </tr>
                                    <tr>
                                        <td>Balance Total</td>
                                        <td><strong>${{number_format($data['balance'],2)}}</strong></td>
                                    </tr>
                                </table>
                            </div>

                        </div>
                        <div class="md:w-1/4">

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

                    <div class="main-table">
                        <div class="table-wrapper">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th class="header">Pago no.</th>
                                    <th class="header">Monto de pago</th>
                                    <th class="header">Pagos realizados</th>
                                </tr>
                                </thead>
                                <tbody>
                                @for ($i = 1; $i <= $data['rows']; $i++)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>${{number_format($data['pago_por_mes'],2)}}</td>
                                        <td>${{(isset($data['pagos'][$i-1]->cantidad)) ? number_format($data['pagos'][$i-1]->cantidad,2) : '0.00'}}</td>
                                    </tr>
                                @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <script>
                        // Get data for chart
                        const totalAmount = {{$data['balance']}};
                        const amountPaid = {{$data['balance_pagado']}};
                        const amountLeft = totalAmount - amountPaid;
                        const percentLeft = (amountLeft / totalAmount) * 100;
                        const percentPaid = 100 - percentLeft;
                        const centerText = `${percentPaid.toFixed(0)}%`;

                        // Create chart
                        const ctx = document.getElementById('car-chart').getContext('2d');
                        const carChart = new Chart(ctx, {
                            type: 'doughnut',
                            data: {
                                labels: ['Cantidad Pagada', 'Cantidad Restante'],
                                datasets: [{
                                    label: 'Pagos',
                                    data: [percentPaid, percentLeft],
                                    backgroundColor: [
                                        'rgba(54, 162, 235, 0.6)',
                                        'rgba(255, 99, 132, 0.6)'
                                    ],
                                    borderColor: [
                                        'rgba(54, 162, 235, 1)',
                                        'rgba(255, 99, 132, 1)'
                                    ],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                legend: {
                                    display: true,
                                    labels: {
                                        fontColor: 'black'
                                    }
                                }
                            }
                        });

                        const chartLabel = document.querySelector('.chart-label');
                        chartLabel.textContent = centerText;

                    </script>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

