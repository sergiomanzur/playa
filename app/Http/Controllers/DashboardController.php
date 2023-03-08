<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class DashboardController extends Controller
{

    public function main(): View
    {

        $user = Auth::user();

        $user->load('lotes', 'lotes.balances', 'lotes.promesas', 'lotes.manzana', 'lotes.pagos');

        $cantidad_de_lotes = count($user->lotes);

        if($cantidad_de_lotes === 1) {
            $lote = $user->lotes->first();
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
        }

        return view('dashboard', [
            'data' => [
                'user' => $user,
                'lote' => $lote,
                'manzana' =>$lote->manzana,
                'balance' => $balance,
                'promesa' => $promesa,
                'credito' => $credito,
                'balance_de_pagos_realizados' => $balance_de_pagos_realizados,
                'balance_pendiente_por_pagar' => $balance_pendiente_por_pagar,
                'balance_a_credito' => $balance_a_credito,
                'balance_pagado' => $amount_paid,
                'porcentaje_por_pagar' => $porcentaje_por_pagar,
                'rows' => $lote->balances->plan_de_pagos,
                'pago_por_mes' => $payment_per_month,
                'pagos' => $pagos
            ]
        ]);
    }
}
