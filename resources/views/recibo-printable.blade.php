<div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div style="padding: 20px;" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <h2 style="font-size: x-large; margin-bottom: 15px;
                 background-color: rgba(28, 152, 131, 1); color: whitesmoke; padding: 20px;">
                    Playa Lum - Recibo de Pago de Mensualidad <span>No.{{$data['num']}}</span>
                </h2>
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p style="font-size: larger"><strong>{{$data['user']['name']}}</strong> - {{$data['user']['username']}}</p>
                    <p>{{$data['manzana']['nombre']}} - {{$data['lote']['nombre']}}</p>
                    <p>Fecha de impresión: {{\Carbon\Carbon::now()->format('d/m/Y')}}</p>
                    <center>
                        <div style="align-items: center; margin-top:35px; justify-content: center;" class="flex flex-col md:flex-col">
                            <p>Hemos recibido tu pago por la cantidad de: ${{number_format($data['pago']['cantidad'],2)}}</p>
                            <img style="width:400px;" src="{{url('/assets/img/firma.png')}}" alt="imagen de la firma"/>
                        </div>
                    </center>
                </div>
            </div>
        </div>
</div>

<style>

    body, p, h2, h3 {
        font-family: 'Nunito', sans-serif;
    }

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

    .payment-info img {
        max-width: 300px;
        display: initial;
    }

</style>
