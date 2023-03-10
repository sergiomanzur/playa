<?php

namespace App\Http\Controllers;

use App\Models\Lote;
use App\Models\Manzana;
use App\Models\Pagos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            $balance = $lote->balances->total ?? '0.00';
            $promesa = $lote->promesas->cantidad ?? '0.00';
            $credito = $lote->balances->credito ?? '0.00';
            $pagos = $lote->pagos;

            $sum_pagos = 0;

            foreach($lote->pagos as $pago) {
                $sum_pagos += $pago->cantidad;
            }

            $balance_de_pagos_realizados = $sum_pagos;
            $balance_pendiente_por_pagar = $balance - $promesa - $balance_de_pagos_realizados;
            $balance_a_credito = $credito;

            if(isset($lote->balances->plan_de_pagos)) {
                $payment_per_month = $credito / $lote->balances->plan_de_pagos;
            } else {
                $payment_per_month = 0.00;
            }

            //(Amount paid / Total worth) x 100%
            $amount_paid = $promesa + $balance_de_pagos_realizados;
            if($balance > 0) {
                $porcentaje_por_pagar = ($amount_paid / $balance) * 100;
            } else {
                $porcentaje_por_pagar = 0;
            }

            $fecha_de_pago_promesa = $lote->promesas->fecha_de_pago;

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
                    'rows' => $lote->balances->plan_de_pagos ?? 1,
                    'pago_por_mes' => $payment_per_month,
                    'pagos' => $pagos,
                    'fecha_de_pago_promesa' => $fecha_de_pago_promesa
                ]
            ]);
        }

        if ($cantidad_de_lotes > 1) {
            $lote = $user->lotes;

            return view('dashboard-multiple', [
                'data' => [
                    'user' => $user,
                    'lotes' => $lote
                ]
            ]);
        }

        return view('dashboard', [
            'data' => [
                'user' => $user,
                'lote' => null,
            ]
        ]);
    }

    public function recibo(Request $request, $pagoId) : View
    {

        $request->validate([

        ]);

        $user = Auth::user();
        $pago = Pagos::where('id', $pagoId)->where('user_id', $user->id)->first();

        if(!is_null($pago)) {
            $lote = Lote::find($pago->lote_id);
        }

        if(!is_null($lote)) {
            $manzana = Manzana::find($lote->manzana_id);
        }

        return view('recibo', [
            'data' => [
                'user' => $user,
                'pago' => $pago,
                'lote' => $lote,
                'manzana' => $manzana
            ]
        ]);
    }
}
