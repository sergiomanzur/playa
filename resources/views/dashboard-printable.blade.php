<style>
    @tailwind base;
    @tailwind components;
    @tailwind utilities;

    main {
        display: flex;
        flex-wrap: wrap;
        padding: 20px;
        margin: 0 auto;
        max-width: 1200px;
    }

    .py-12 {
        width: 100%;
    }

    content {
        flex: 2;
        padding: 20px;
    }

    h3 {
        border-bottom: 1px solid;
        margin-bottom: 5px;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        margin-bottom: 1rem;

    }

    table th,
    table td {
        padding: 0.75rem;
        text-align: left;
        vertical-align: top;
        border-top: 1px solid #dee2e6;
    }


    .chartjs-doughnut-chart .chartjs-center-text h2 {
        font-size: 50px;
        font-weight: bold;
        color: #36A2EB; /* You can customize the color here */
    }

    @media only screen and (max-width: 768px) {
        content {
            flex-direction: column;
        }
    }


    .chart-container {
        position: relative;
    }

    .chart-label {
        position: absolute;
        top: 56%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 48px;
        font-weight: bold;
        /*color: #36A2EB;*/
    }

    .payment-info {
        margin: auto;
        text-align: center;
        width: 50%;
    }

    .payment-info img {
        max-width: 300px;
        display: initial;
    }

    .page_break { page-break-before: always; }

</style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 style="font-size: x-large; border-bottom: 1px solid; margin-bottom: 15px;
                    background-color: rgba(28, 152, 131, 1);padding: 15px;">
                        Bienvenido a tu Estado de Cuenta de Playa Hermosa
                    </h2>
                    @if(!is_null($data['lote']))
                    <p style="font-size: larger"><strong>{{$data['user']['name']}}</strong> - {{$data['user']['username']}}</p>
                    <p>{{$data['manzana']['nombre']}} - {{$data['lote']['nombre']}}</p>
                    <div class="flex flex-col md:flex-row">
                        <div  class="md:w-3/4 p-4">
                            <div style="width:100%; margin: auto; text-align: center; margin-top: 15px;">
                                <?php
                                    $totalAmount = $data['balance'];
                                    $amountPaid = $data['balance_pagado'];
                                    $amountLeft = $totalAmount - $amountPaid;
                                    $percentLeft = ceil(($amountLeft / $totalAmount) * 100);
                                    $percentPaid = ceil(100 - $percentLeft);
                                    ?>
                                <img src="https://quickchart.io/chart?c={type:'doughnut',data:{labels:['Porcentaje Pagado', 'Porcentaje Restante'],datasets:[{label:'Pagos',data:[{{$percentPaid}},{{$percentLeft}}], backgroundColor:['rgba(217, 240, 240, 1)','rgba(28, 152, 131, 1)'],borderColor:['black','black'],borderWidth:1}]}}">

                            </div>
                            <div style="margin:auto; margin-top: 20px; padding-right: 5%; padding-left: 5%">
                                <table>
                                    <tr>
                                        <td>Enganche</td>
                                        <td><strong>${{number_format($data['promesa'],2)}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Saldo a crédito (meses)</td>
                                        <td><strong>${{number_format($data['credito'],2)}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Valor Terreno</td>
                                        <td><strong>${{number_format($data['balance'],2)}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Balance a crédito</td>
                                        <td><strong>${{number_format($data['balance_a_credito'],2)}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Balance de Pagos Realizados</td>
                                        <td><strong>${{number_format($data['balance_de_pagos_realizados'],2)}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Balance Pendiente Por Pagar</td>
                                        <td><strong>${{number_format($data['balance_pendiente_por_pagar'],2)}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Concepto o Motivo de Pago</td>
                                        <td><strong>{{$data['user']['username']}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Fecha de Inicio del Contrato</td>
                                        <td><strong>{{\Carbon\Carbon::parse($data['fecha_de_pago_promesa'])->format('d/m/Y')}}</strong></td>
                                    </tr>
                                        <?php if(!is_null($data['interes'])) { ?>
                                            <tr>
                                                <td>Interés Anual</td>
                                                <td><strong>{{$data['interes']['interes']}}%</strong></td>
                                            </tr>
                                        <?php } ?>

                                </table>
                            </div>

                        </div>
                    </div>

                    <div class="page_break"></div>

                    <div class="main-table">
                        <div class="table-wrapper" style="padding-left: 15%; padding-right: 15%">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th class="header">Mensualidad</th>
                                    <th class="header">Monto a pagar</th>
                                    <th class="header">Pagos realizados</th>
                                    <th class="header">Saldo</th>
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
                                    </tr>
                                @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>

                        @if($rows > 12)
                            <div class="page_break"></div>
                        @endif

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


