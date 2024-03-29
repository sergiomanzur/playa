<?php

namespace App\Http\Controllers;

use App\Models\Balances;
use App\Models\Lote;
use App\Models\Manzana;
use App\Models\Pagos;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    var $allowedEmails = ['sergiom2010@gmail.com', 'jprubio90@icloud.com', 'eduagonmon@gmail.com',
        'baruch.barrera@gmail.com', 'admin@playahermosa.mx'];

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

            $payment_per_month = 0.00;

            if(isset($lote->balances->plan_de_pagos)) {
                if($lote->balances->plan_de_pagos != 'libre') {
                    $payment_per_month = $credito / $lote->balances->plan_de_pagos;
                }
            }

            //(Amount paid / Total worth) x 100%
            $amount_paid = $promesa + $balance_de_pagos_realizados;
            if($balance > 0) {
                $porcentaje_por_pagar = ($amount_paid / $balance) * 100;
            } else {
                $porcentaje_por_pagar = 0;
            }

            if(!is_null($lote->promesas)) {
                $fecha_de_pago_promesa = $lote->promesas->fecha_de_pago;
            } else {
                $fecha_de_pago_promesa = null;
            }

            if(!is_null($lote->balances)) {
                $interes = $lote->balances->interes;
            } else {
                $interes = null;
            }

            $pago_mensual = null;
            if(!is_null($interes) && $lote->balances->plan_de_pagos != 'libre') {
                $interes = $interes->interes;
                $interes_anual = $interes / 100;
                $plazos = $lote->balances->plan_de_pagos;
                $interes_mensual = $interes_anual / 12;
                $base = pow(1 + $interes_mensual, $plazos);
                $pago_mensual = ($credito * $interes_mensual * $base) / ($base - 1);
            }

            return view('dashboard', [
                'data' => [
                    'user' => $user,
                    'lote' => $lote,
                    'manzana' =>$lote->manzana,
                    'balance' => $balance,
                    'tiene_deuda' => $lote->balances->tiene_deuda ?? null,
                    'promesa' => $promesa,
                    'credito' => $credito,
                    'balance_de_pagos_realizados' => $balance_de_pagos_realizados,
                    'balance_pendiente_por_pagar' => $balance_pendiente_por_pagar,
                    'balance_a_credito' => $balance_a_credito,
                    'balance_pagado' => $amount_paid,
                    'porcentaje_por_pagar' => $porcentaje_por_pagar,
                    'rows' => $lote->balances->plan_de_pagos ?? count($pagos),
                    'pago_por_mes' => $payment_per_month,
                    'pagos' => $pagos,
                    'fecha_de_pago_promesa' => $fecha_de_pago_promesa,
                    'interes' => $interes,
                    'pago_mensual' => $pago_mensual,
                    'balance_id' => $lote->balances->id ?? null
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

    public function recibo(Request $request, $pagoId)
    {

        $request->validate([
            'download' => 'boolean|sometimes',
            'num' => 'integer'
        ]);

        $user = Auth::user();
        $pago = Pagos::where('id', $pagoId)->where('user_id', $user->id)->first();

        $num = $request->input('num');

        if(!is_null($pago)) {
            $lote = Lote::find($pago->lote_id);
        }

        if(!is_null($lote)) {
            $manzana = Manzana::find($lote->manzana_id);
        }


        if($request->has('download')){
            if($request->input('download')) {
                $pdf = Pdf::loadView('recibo-printable', [
                    'data' => [
                        'user' => $user,
                        'pago' => $pago,
                        'lote' => $lote,
                        'manzana' => $manzana,
                        'num' => $num
                    ]
                ]);
                $pdf->setOption(['dpi' => 150, 'defaultFont' => 'Nunito sans-serif']);
                return $pdf->download('recibo-'.$user->username.'-'.$pagoId.'.pdf');
            }
        }

        return view('recibo', [
            'data' => [
                'user' => $user,
                'pago' => $pago,
                'lote' => $lote,
                'manzana' => $manzana,
                'num' => $num
            ]
        ]);
    }

    public function printBalance(Request $request, $balanceId)
    {
        $request->validate([
            'download' => 'boolean|sometimes',
            'user_id' => 'int|sometimes'
        ]);

        if($request->has('user_id')) {
            $user = User::find($request->input('user_id'));
        } else {
            $user = Auth::user();
        }

        $balance = Balances::where('id', $balanceId)->where('user_id', $user->id)->first();
        $lote = null;

        if(!is_null($balance)) {
            $lote = Lote::where('id',$balance->lote_id)->first();
        }

        if(!is_null($lote)) {
            $promesa = $lote->promesas->cantidad ?? '0.00';
            $credito = $lote->balances->credito ?? '0.00';
            $pagos = $lote->pagos;

            $balance = $balance->total;

            $sum_pagos = 0;

            foreach($lote->pagos as $pago) {
                $sum_pagos += $pago->cantidad;
            }

            $balance_de_pagos_realizados = $sum_pagos;
            $balance_pendiente_por_pagar = $balance - $promesa - $balance_de_pagos_realizados;
            $balance_a_credito = $credito;

            $payment_per_month = 0.00;

            if(isset($lote->balances->plan_de_pagos)) {
                if($lote->balances->plan_de_pagos != 'libre') {
                    $payment_per_month = $credito / $lote->balances->plan_de_pagos;
                }
            }

            //(Amount paid / Total worth) x 100%
            $amount_paid = $promesa + $balance_de_pagos_realizados;
            if($balance > 0) {
                $porcentaje_por_pagar = ($amount_paid / $balance) * 100;
            } else {
                $porcentaje_por_pagar = 0;
            }

            $fecha_de_pago_promesa = $lote->promesas->fecha_de_pago;

            $interes = $lote->balances->interes;

            $pago_mensual = null;

            if(!is_null($interes) && $lote->balances->plan_de_pagos != 'libre') {
                $interes = $interes->interes;
                $interes_anual = $interes / 100;
                $plazos = $lote->balances->plan_de_pagos;
                $interes_mensual = $interes_anual / 12;
                $base = pow(1 + $interes_mensual, $plazos);
                $pago_mensual = ($credito * $interes_mensual * $base) / ($base - 1);
            }


            if($request->has('download')){
                if($request->input('download')) {
                    $pdf = Pdf::loadView('dashboard-printable', [
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
                            'rows' => $lote->balances->plan_de_pagos ?? count($pagos),
                            'pago_por_mes' => $payment_per_month,
                            'pagos' => $pagos,
                            'fecha_de_pago_promesa' => $fecha_de_pago_promesa,
                            'interes' => $interes,
                            'pago_mensual' => $pago_mensual
                        ]
                    ])
                        ->setOption(['dpi' => 150, 'defaultFont' => 'Nunito sans-serif']);

                    return $pdf->download('estado-de-cuenta-'.$user->username.'-'.$balanceId.'-'.Carbon::now().'.pdf');
                }
            }
        }

        return response()->json([
            'error' => 'Error al imprimir balance',
            'balance' => $balance,
            'lote' => $lote
        ]);

    }

    public function cuentaMadre() {
        $allowedEmails = $this->allowedEmails;
        $user = Auth::user();

        if(is_null($user)) {
            abort(403); // unauthorized
        }

        // check if user's email is allowed
        if (!in_array($user->email, $allowedEmails)) {
            abort(403); // unauthorized
        }

        // if allowed, retrieve list of users
        $users = User::all();

        // create options for select input
        $options = '';
        foreach ($users as $u) {
            $options .= '<option value="' . $u->id . '">' . $u->name . ' - ID: ' . $u->id . '</option>';
        }

        // show blade view
        return view('cuentaMadre.index')->with('options', $options);
    }

    public function cuentaMadreDashboard(Request $request) {
        $request->validate([
            'user_id' => 'integer|required'
        ]);

        $allowedEmails = $this->allowedEmails;
        $loggedUser = Auth::user();

        if(is_null($loggedUser)) {
            abort(403); // unauthorized
        }

        // check if user's email is allowed
        if (!in_array($loggedUser->email, $allowedEmails)) {
            abort(403); // unauthorized
        }


        $user_id = $request->input('user_id');
        $user = User::find($user_id);


        if(is_null($user)) {
            abort(400); // user not found
        }

        $user->load('lotes', 'lotes.promesas', 'lotes.manzana', 'lotes.pagos');

        $cantidad_de_lotes = count($user->lotes);

        $balances = Balances::where('lote_id', $user->lotes->first()->id)->first();

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

            $payment_per_month = 0.00;

            if(isset($lote->balances->plan_de_pagos)) {
                if($lote->balances->plan_de_pagos != 'libre') {
                    $payment_per_month = $credito / $lote->balances->plan_de_pagos;
                }
            }

            //(Amount paid / Total worth) x 100%
            $amount_paid = $promesa + $balance_de_pagos_realizados;
            if($balance > 0) {
                $porcentaje_por_pagar = ($amount_paid / $balance) * 100;
            } else {
                $porcentaje_por_pagar = 0;
            }

            if(!is_null($lote->promesas)) {
                $fecha_de_pago_promesa = $lote->promesas->fecha_de_pago;
            } else {
                $fecha_de_pago_promesa = null;
            }

            if(!is_null($lote->balances)) {
                $interes = $lote->balances->interes;
            } else {
                $interes = null;
            }

            $pago_mensual = null;
            if(!is_null($interes) && $lote->balances->plan_de_pagos != 'libre') {
                $interes = $interes->interes;
                $interes_anual = $interes / 100;
                $plazos = $lote->balances->plan_de_pagos;
                $interes_mensual = $interes_anual / 12;
                $base = pow(1 + $interes_mensual, $plazos);
                $pago_mensual = ($credito * $interes_mensual * $base) / ($base - 1);
            }


            return view('dashboard', [
                'data' => [
                    'user' => $user,
                    'lote' => $lote,
                    'manzana' =>$lote->manzana,
                    'balance' => $balance,
                    'tiene_deuda' => $lote->balances->tiene_deuda ?? null,
                    'promesa' => $promesa,
                    'credito' => $credito,
                    'balance_de_pagos_realizados' => $balance_de_pagos_realizados,
                    'balance_pendiente_por_pagar' => $balance_pendiente_por_pagar,
                    'balance_a_credito' => $balance_a_credito,
                    'balance_pagado' => $amount_paid,
                    'porcentaje_por_pagar' => $porcentaje_por_pagar,
                    'rows' => $lote->balances->plan_de_pagos ?? count($pagos),
                    'pago_por_mes' => $payment_per_month,
                    'pagos' => $pagos,
                    'fecha_de_pago_promesa' => $fecha_de_pago_promesa,
                    'interes' => $interes,
                    'pago_mensual' => $pago_mensual,
                    'balance_id' => $lote->balances->id ?? null
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

}
