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
                    <h2 style="font-weight:bolder;font-size: x-large; border-bottom: 1px solid; margin-bottom: 15px;
                    background-color: rgba(28, 152, 131, 1);padding: 15px;color:white;">
                        Estado de Cuenta Playa Hermosa
                    </h2>
                    @if(isset($data['tiene_deuda']))
                        @if($data['tiene_deuda'])
                            <div style="text-align: center; margin-top: -20px; margin-bottom:20px;background-color: red;color: white;">
                                Estimado cliente, tienes Mensualidad(es) pendiente(s) por pagar, favor de aclarar al numero (833)259-97-76
                            </div>
                        @endif
                    @endif
                    @if(!is_null($data['lote']))
                        <p style="font-size: larger"><strong>{{$data['user']['name']}}</strong> - <span style="color: #dba265; font-weight: bolder;">{{$data['user']['username']}}</span></p>
                    <p>{{$data['manzana']['nombre']}} - {{$data['lote']['nombre']}}</p>
                    <div class="flex flex-col md:flex-row">
                        <div  class="md:w-3/4 p-4">
                            @if(!is_null($data['balance_id']))
                            <a style="color: #36A2EB; text-underline: #36A2EB"
                               href="/estados-de-cuenta/{{$data['balance_id']}}?download=1">Descargar</a>
                            <div style="width:100%; margin: auto; text-align: center; margin-top: 15px;">
                                <div class="chart-container">
                                    <h2 class="chart-label"></h2>
                                    <canvas id="car-chart" style="max-width:100%;max-height: 300px;">
                                    </canvas>
                                </div>
                                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                            </div>
                            @endif
                            <div style="margin:auto; margin-top: 20px; padding-left: 18%">
                                <table>
                                    <tr>
                                        <td>Enganche</td>
                                        <td>${{number_format($data['promesa'],2)}}</td>
                                    </tr>
                                    <tr>
                                        <td>Saldo a crédito (meses)</td>
                                        <td>${{number_format($data['credito'],2)}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Valor Terreno</strong></td>
                                        <td><strong>${{number_format($data['balance'],2)}}</strong></td>
                                    </tr>
                                </table>
                            </div>

                        </div>
                        <div class="md:w-1/4" style="padding-top: 2rem;padding-right: 35px;">

                            <h3>Balance a crédito</h3>
                            <p>${{number_format($data['balance_a_credito'],2)}}</p>

                            <br/>

                            <h3>Balance de pagos realizados</h3>
                            <p>${{number_format($data['balance_de_pagos_realizados'],2)}}</p>

                            <br/>

                            <h3>Balance pendiente por pagar</h3>
                            <p>${{number_format($data['balance_pendiente_por_pagar'],2)}}</p>

                            <br/>

                            <h3>Concepto o Motivo de Pago</h3>
                            <p>{{$data['user']['username']}}</p>

                            <br/>

                            @if(isset($data['fecha_de_pago_promesa']))
                            <h3>Fecha de Inicio del Contrato</h3>
                            <p>{{\Carbon\Carbon::parse($data['fecha_de_pago_promesa'])->format('d/m/Y')}}</p>
                            @endif

                            <br/>

                            <?php if(!is_null($data['interes'])) { ?>
                                <h3>Interés Anual</h3>
                            <?php if(is_array($data['interes']) || is_object($data['interes'])) { ?>
                                <p>{{$data['interes']['interes']}}%</p>
                            <?php } else { ?>
                            <p>{{$data['interes']}}</p>
                            <?php } ?>

                            <br/>

                            <?php } ?>


                            <br/>
                            <br/>
                            <br/>


                        </div>
                    </div>

                    <div class="main-table">
                        <div class="table-wrapper" style="padding-left: 15%; padding-right: 15%">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th class="header">Mensualidad</th>
                                    <th class="header">Monto a pagar</th>
                                    <th class="header">Pagos realizados</th>
                                    <th class="header">Saldo</th>
                                    <th class="header">Recibo</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php ($data['rows'] === 'libre') ? $rows = count($data['pagos']) : $rows = $data['rows'];  ?>
                                @for ($i = 1; $i <= $rows; $i++)
                                        <?php isset($data['pagos'][$i-1]->cantidad) ? $credito = $data['credito'] - $data['pagos'][$i-1]->cantidad : $credito = $data['credito'] ?>
                                    <tr>
                                        <td>No. {{ $i }}</td>
                                        @if(!is_null($data['pago_mensual']))
                                            <td>${{number_format($data['pago_mensual'],2)}}</td>
                                        @else
                                            <td>${{number_format($data['pago_por_mes'],2)}}</td>
                                        @endif
                                        <td>${{(isset($data['pagos'][$i-1]->cantidad)) ? number_format($data['pagos'][$i-1]->cantidad,2) : '0.00'}}</td>
                                        <td>${{number_format($credito,2)}}</td>
                                        <td> @if(isset($data['pagos'][$i-1])) <a href="/recibos/{{$data['pagos'][$i - 1]->id}}">Ver</a> / Descargar @else <p>No realizado</p> @endif</td>
                                    </tr>
                                @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>

                        <div class="payment-info">
                            <h3>Cuenta Santander</h3>
                            <p>014813606269521266</p>

                            <br/>

                            <h3>Cuenta Bancomer</h3>
                            <p>012813004771422347</p>

                            <br/>

                            <img style="width:100%;" src="{{url('/assets/img/firma.png')}}" alt="imagen de la firma"/>
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
                                        'rgba(217, 240, 240, 1)',
                                        'rgba(28, 152, 131, 1)'
                                    ],
                                    borderColor: [
                                        'black',
                                        'black'
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
                    @else
                        <p>Actualmente no tienes ningún lote dado de alta.</p>
                        <p>Regresa Pronto</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

