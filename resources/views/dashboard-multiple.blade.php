<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Panel Principal') }}
        </h2>
    </x-slot>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 style="font-size: x-large; border-bottom: 1px solid; margin-bottom: 15px;
                    background-color: rgba(28, 152, 131, 1);padding: 15px; color: white;">
                        Bienvenido a tu Estado de Cuenta de Playa Hermosa
                    </h2>
                    <p style="font-size: larger">
                        <strong>{{$data['user']['name']}}</strong> - {{$data['user']['username']}}
                    </p>

                    <?php

                        foreach ($data['lotes'] as $lote) {

                            $balance = $lote->balances->total;
                            $promesa = $lote->promesas->cantidad;
                            $credito = $lote->balances->credito;
                            $pagos = $lote->pagos;

                            $sum_pagos = 0;

                            foreach($lote->pagos as $pago) {
                                $sum_pagos += $pago->cantidad;
                            }

                            $balance_de_pagos_realizados = $sum_pagos;
                            $balance_pendiente_por_pagar = $balance - $promesa - $balance_de_pagos_realizados;
                            $balance_a_credito = $credito;

                            $payment_per_month = $credito / $lote->balances->plan_de_pagos;

                            //(Amount paid / Total worth) x 100%
                            $amount_paid = $promesa + $balance_de_pagos_realizados;
                            $porcentaje_por_pagar = ($amount_paid / $balance) * 100;

                            $fecha_de_pago_promesa = $lote->promesas->fecha_de_pago;

                            $interes = $lote->balances->interes;

                            $pago_mensual = null;
                            if(!is_null($interes)) {
                                $interes = $interes->interes;
                                $interes_anual = 0.085;
                                $plazos = $lote->balances->plan_de_pagos;
                                $interes_mensual = $interes_anual / 12;
                                $base = pow(1 + $interes_mensual, $plazos);
                                $pago_mensual = ($credito * $interes_mensual * $base) / ($base - 1);
                            }
                            ?>

                    <div class="collapsible" id="collapsible{{$lote->id}}">
                        <div class="collapsible-header dark:bg-gray-900">{{$lote->manzana->nombre}} - {{$lote->nombre}}</div>
                        <div class="collapsible-body" style="display: none;">
                            <div class="flex flex-col md:flex-row">
                                <div  class="md:w-3/4 p-4">
                                    <a style="color: #36A2EB; text-underline: #36A2EB"
                                       href="/estados-de-cuenta/{{$lote->balances->id}}?download=1">Descargar</a>
                                    <div style="width:100%; margin: auto; text-align: center; margin-top: 15px;">
                                        <div class="chart-container">
                                            <h2 class="chart-label ch-label{{$lote->id}}"></h2>
                                            <canvas id="car-chart{{$lote->id}}" style="max-width:100%;max-height: 300px;">
                                            </canvas>
                                        </div>
                                    </div>
                                    <div style="margin:auto; margin-top: 20px; padding-right: 25%; padding-left: 25%">
                                        <table>
                                            <tr>
                                                <td>Enganche</td>
                                                <td>${{number_format($promesa,2)}}</td>
                                            </tr>
                                            <tr>
                                                <td>Saldo a crédito (meses)</td>
                                                <td>${{number_format($credito,2)}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Valor Terreno</strong></td>
                                                <td><strong>${{number_format($balance,2)}}</strong></td>
                                            </tr>
                                        </table>
                                    </div>

                                </div>
                                <div class="md:w-1/4" style="padding-top: 2rem;">

                                    <h3>Balance a crédito</h3>
                                    <p>${{number_format($balance_a_credito,2)}}</p>

                                    <br/>

                                    <h3>Balance de pagos realizados</h3>
                                    <p>${{number_format($balance_de_pagos_realizados,2)}}</p>

                                    <br/>

                                    <h3>Balance pendiente por pagar</h3>
                                    <p>${{number_format($balance_pendiente_por_pagar,2)}}</p>

                                    <br/>

                                    <h3>Concepto o Motivo de Pago</h3>
                                    <p>{{$data['user']['username']}}</p>

                                    <br/>

                                    @if(!is_null($fecha_de_pago_promesa))
                                    <h3>Fecha de Inicio del Contrato</h3>
                                    <p>{{\Carbon\Carbon::parse($fecha_de_pago_promesa)->format('d/m/Y')}}</p>
                                    @endif

                                    <br/>

                                    <?php if(!is_null($interes)) { ?>
                                    <h3>Interés Anual</h3>
                                    <p>{{$interes}}%</p>

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
                                        @for ($i = 1; $i <= $lote->balances->plan_de_pagos; $i++)
                                            <?php isset($pagos[$i-1]->cantidad) ? $credito = $credito - $pagos[$i-1]->cantidad : $credito ?>
                                            <tr>
                                                <td>No. {{ $i }}</td>
                                                @if(is_null($pago_mensual))
                                                <td>${{number_format($payment_per_month,2)}}</td>
                                                @else
                                                    <td>${{number_format($pago_mensual,2)}}</td>
                                                @endif
                                                <td>${{(isset($pagos[$i-1]->cantidad)) ?
                                                    number_format($pagos[$i-1]->cantidad,2) : '0.00'}}
                                                </td>
                                                <td>${{number_format($credito,2)}}</td>
                                                <td>@if(isset($pagos[$i-1]))
                                                        <a href="/recibos/{{$pagos[$i - 1]->id}}">Ver</a>
                                                        / <a href="/recibos/{{$pagos[$i - 1]->id}}?download=1">
                                                            Descargar</a>
                                                    @endif
                                                </td>
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
                        </div>

                    </div>

                    <script>
                        // Get data for chart
                        var totalAmount = {{$balance}};
                        var amountPaid = {{$amount_paid}};
                        var amountLeft = totalAmount - amountPaid;
                        var percentLeft = (amountLeft / totalAmount) * 100;
                        var percentPaid = 100 - percentLeft;
                        var centerText = `${percentPaid.toFixed(0)}%`;

                        // Create chart
                        var ctx = document.getElementById('car-chart{{$lote->id}}').getContext('2d');
                        var carChart = new Chart(ctx, {
                            type: 'doughnut',
                            data: {
                                labels: ['Porcentaje Pagado', 'Porcentaje Restante'],
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

                        var chartLabel = document.querySelector('.ch-label{{$lote->id}}');
                        chartLabel.textContent = centerText;

                    </script>

                    <?php

                        }

                    ?>

                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var collapsibles = document.querySelectorAll('.collapsible');
                        for (var i = 0; i < collapsibles.length; i++) {
                            var header = collapsibles[i].querySelector('.collapsible-header');
                            header.addEventListener('click', function() {
                                var collapsible = this.parentElement;
                                collapsible.classList.toggle('active');
                                var content = collapsible.querySelector('.collapsible-body');
                                if (content.style.display === 'block') {
                                    content.style.display = 'none';
                                } else {
                                    content.style.display = 'block';
                                }
                            });
                        }
                    });
                </script>
            </div>
        </div>
    </div>

</x-app-layout>

