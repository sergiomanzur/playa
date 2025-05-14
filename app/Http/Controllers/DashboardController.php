<?php

namespace App\Http\Controllers;

use App\Models\Balances;
use App\Models\Lote;
use App\Models\Manzana;
use App\Models\Pagos;
use App\Models\User;
use App\Models\CargoAdicional; // Added CargoAdicional
use App\Models\PagoCargoAdicional; // Added PagoCargoAdicional
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    private array $allowedEmails = ['sergiom2010@gmail.com', 'jprubio90@icloud.com', 'eduagonmon@gmail.com',
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

            // Calculate sum of cargos adicionales
            $cargos_adicionales_list = CargoAdicional::where('user_id', $user->id)
                                                    ->get();
            $sum_cargos_adicionales = $cargos_adicionales_list->sum('total');

            // Get pagos for these cargos adicionales
            $cargo_ids = $cargos_adicionales_list->pluck('id');
            $pagos_cargos_adicionales_list = PagoCargoAdicional::with('cargoAdicional') // Eager load cargoAdicional
                                                                ->whereIn('cargo_adicional_id', $cargo_ids)
                                                                ->orderBy('created_at', 'desc') // Order by creation date
                                                                ->get();
            $sum_pagos_cargos_adicionales = $pagos_cargos_adicionales_list->sum('total');

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
                    'balance_id' => $lote->balances->id ?? null,
                    'isCuentaMadre' => false,
                    'sum_cargos_adicionales' => $sum_cargos_adicionales ?? 0,
                    'cargos_adicionales_list' => $cargos_adicionales_list,
                    'pagos_cargos_adicionales_list' => $pagos_cargos_adicionales_list,
                    'sum_pagos_cargos_adicionales' => $sum_pagos_cargos_adicionales ?? 0,
                ]
            ]);
        }

        if ($cantidad_de_lotes > 1) {
            $lote = $user->lotes;

            return view('dashboard-multiple', [
                'data' => [
                    'user' => $user,
                    'lotes' => $lote,
                    'isCuentaMadre' => false,
                ]
            ]);
        }
        // Fallback for no lotes
        $cargos_adicionales_list = collect();
        $sum_cargos_adicionales = 0;
        $pagos_cargos_adicionales_list = collect();
        $sum_pagos_cargos_adicionales = 0;

        return view('dashboard', [
            'data' => [
                'user' => $user,
                'lote' => null,
                'isCuentaMadre' => false,
                'sum_cargos_adicionales' => $sum_cargos_adicionales,
                'cargos_adicionales_list' => $cargos_adicionales_list,
                'pagos_cargos_adicionales_list' => $pagos_cargos_adicionales_list,
                'sum_pagos_cargos_adicionales' => $sum_pagos_cargos_adicionales,
            ]
        ]);
    }

    public function recibo(Request $request, $pagoId)
    {
        $lote = null;
        $manzana = null;
        $targetUser = null;

        $request->validate([
            'download' => 'boolean|sometimes',
            'num' => 'integer|sometimes',
            'user_id' => 'integer|sometimes' // For cuentaMadre context
        ]);

        $loggedInUser = Auth::user();
        if (!$loggedInUser) {
            abort(401, 'Unauthorized');
        }

        if ($request->has('user_id')) {
            // If user_id is present, it's a cuentaMadre context or similar admin view
            // Check if the logged-in user is authorized to perform this action
            if (!in_array($loggedInUser->email, $this->allowedEmails)) {
                abort(403, 'Forbidden: You are not authorized to view this recibo.');
            }
            $targetUser = User::find($request->input('user_id'));
            if (!$targetUser) {
                abort(404, 'User specified for recibo not found.');
            }
        } else {
            // Normal user context
            $targetUser = $loggedInUser;
        }

        // Fetch pago for the targetUser
        $pago = Pagos::where('id', $pagoId)->where('user_id', $targetUser->id)->first();
        $num = $request->input('num');

        if(!is_null($pago)) {
            $lote = Lote::find($pago->lote_id);
            if(!is_null($lote)) {
                $manzana = Manzana::find($lote->manzana_id);
            }
        } else {
            // If pago is not found for the user, it could be an access attempt to a non-owned/non-existent recibo
            // For a web view, redirecting back with an error is often better than aborting, unless it's a clear unauthorized access
            if ($request->has('download') && $request->input('download')) {
                 return redirect()->back()->with('error', 'Recibo no encontrado o no tiene permiso para verlo.');
            }
            // For the HTML view, let it proceed to the view, which should handle null $pago gracefully
        }


        if($request->has('download') && $request->input('download')) {
            if(!is_null($pago) && !is_null($lote) && !is_null($manzana)) { // Ensure all data is present for PDF
                $pdf = Pdf::loadView('recibo-printable', [
                    'data' => [
                        'user' => $targetUser,
                        'pago' => $pago,
                        'lote' => $lote,
                        'manzana' => $manzana,
                        'num' => $num
                    ]
                ]);
                $pdf->setOption(['dpi' => 150, 'defaultFont' => 'Nunito sans-serif']);
                return $pdf->download('recibo-'.$targetUser->username.'-'.$pagoId.'.pdf');
            } else {
                return redirect()->back()->with('error', 'No se pudo generar el PDF del recibo por falta de datos.');
            }
        }

        // For the HTML view, pass data, view should handle if $pago, $lote, or $manzana are null
        return view('recibo', [
            'data' => [
                'user' => $targetUser, // $targetUser will always be set or aborted
                'pago' => $pago,       // Can be null
                'lote' => $lote,       // Can be null
                'manzana' => $manzana, // Can be null
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

        $userForBalance = null;
        $loggedInUser = Auth::user();


        if (!$loggedInUser) {
            abort(401, 'Unauthorized');
        }

        if($request->has('user_id')) {
            $userForBalance = User::find($request->input('user_id'));
            if (!$userForBalance) {
                abort(404, 'User specified for balance not found.');
            }
        } else {
            $userForBalance = $loggedInUser;
        }

        $balanceModel = Balances::where('id', $balanceId)
                                ->where('user_id', $userForBalance->id)
                                ->with(['lote.promesas', 'lote.manzana', 'lote.pagos', 'interes']) // Eager load relations
                                ->first();

        $cargos_adicionales_list = CargoAdicional::where('user_id', $userForBalance->id)->get();
        $sum_cargos_adicionales = $cargos_adicionales_list->sum('total');

        $lote = null;
        $balanceAmount = '0.00';

        if(!is_null($balanceModel)) {
            $lote = $balanceModel->lote; // Access eager loaded relation
            $balanceAmount = $balanceModel->total;
        } else {
            if ($request->has('download') && $request->input('download')) {
                return redirect()->back()->with('error', 'Balance no encontrado, no se puede generar el PDF.');
            }
            return response()->json([
                'error' => 'Balance no encontrado.',
                'balance' => null,
                'lote' => null
            ], 404);
        }

        if(!is_null($lote)) {
            $promesa = $lote->promesas->cantidad ?? '0.00';
            $credito = $balanceModel->credito ?? '0.00';
            // Pagos should be filtered for the specific lote if not already implied by $lote->pagos relation
            // Assuming $lote->pagos are specific to this lote. If Pagos are directly related to User and Lote,
            // ensure $balanceModel->lote->pagos are correctly filtered or fetch them: $userForBalance->pagos()->where('lote_id', $lote->id)->get();
            $pagos = $lote->pagos;

            $sum_pagos = 0;
            foreach($pagos as $pago) {
                $sum_pagos += $pago->cantidad;
            }

            $balance_de_pagos_realizados = $sum_pagos;
            $balance_pendiente_por_pagar = $balanceAmount - $promesa - $balance_de_pagos_realizados;
            $balance_a_credito = $credito;

            $payment_per_month = 0.00;
            if(isset($balanceModel->plan_de_pagos) && $balanceModel->plan_de_pagos != 'libre' && $balanceModel->plan_de_pagos > 0 && $credito > 0) {
                $payment_per_month = $credito / $balanceModel->plan_de_pagos;
            }

            $amount_paid = $promesa + $balance_de_pagos_realizados;
            $porcentaje_por_pagar = ($balanceAmount > 0) ? ($amount_paid / $balanceAmount) * 100 : 0;

            $fecha_de_pago_promesa = $lote->promesas->fecha_de_pago ?? null;
            $interesData = $balanceModel->interes;
            $interesRate = null;
            $pago_mensual = null;

            if(!is_null($interesData) && isset($balanceModel->plan_de_pagos) && $balanceModel->plan_de_pagos != 'libre' && $balanceModel->plan_de_pagos > 0 && $credito > 0) {
                $interesRate = $interesData->interes;
                $interes_anual = $interesRate / 100;
                $plazos = $balanceModel->plan_de_pagos;
                $interes_mensual = $interes_anual / 12;

                if ($interes_mensual > 0) {
                    $base = pow(1 + $interes_mensual, $plazos);
                    if ($base != 1) {
                         $pago_mensual = ($credito * $interes_mensual * $base) / ($base - 1);
                    }
                } else if ($interes_mensual == 0) { // No interest or 0% interest
                     $pago_mensual = $credito / $plazos;
                } // If $interes_mensual < 0, this formula might not be appropriate, consider business logic.
            } else if (isset($balanceModel->plan_de_pagos) && $balanceModel->plan_de_pagos != 'libre' && $balanceModel->plan_de_pagos > 0 && $credito > 0) {
                 // No interest record, but there is a plan and credit
                 $pago_mensual = $credito / $balanceModel->plan_de_pagos;
            }

            if($request->has('download') && $request->input('download')) {
                $pdf = Pdf::loadView('dashboard-printable', [
                    'data' => [
                        'user' => $userForBalance,
                        'lote' => $lote,
                        'manzana' =>$lote->manzana,
                        'balance' => $balanceAmount,
                        'promesa' => $promesa,
                        'credito' => $credito,
                        'balance_de_pagos_realizados' => $balance_de_pagos_realizados,
                        'balance_pendiente_por_pagar' => $balance_pendiente_por_pagar,
                        'balance_a_credito' => $balance_a_credito,
                        'balance_pagado' => $amount_paid,
                        'porcentaje_por_pagar' => $porcentaje_por_pagar,
                        'rows' => $balanceModel->plan_de_pagos ?? count($pagos),
                        'pago_por_mes' => $payment_per_month,
                        'pagos' => $pagos,
                        'fecha_de_pago_promesa' => $fecha_de_pago_promesa,
                        'interes' => $interesRate,
                        'pago_mensual' => $pago_mensual,
                        'cargos_adicionales_list' => $cargos_adicionales_list,
                        'sum_cargos_adicionales' => $sum_cargos_adicionales ?? 0,
                    ]
                ])
                    ->setOption(['dpi' => 150, 'defaultFont' => 'Nunito sans-serif']);

                return $pdf->download('estado-de-cuenta-'.$userForBalance->username.'-'.$balanceId.'-'.Carbon::now()->format('YmdHis').'.pdf');
            }
        } else if ($request->has('download') && $request->input('download')) {
            return redirect()->back()->with('error', 'Lote no encontrado para este balance, no se puede generar el PDF.');
        }

        return response()->json([
            'message' => 'Balance information retrieved.',
            'balance' => $balanceModel,
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

        $allowedEmails = $this->allowedEmails; // Fixed syntax error here
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

        // It's safer to check if lotes collection is not empty before accessing first()
        $firstLote = $user->lotes->isNotEmpty() ? $user->lotes->first() : null;
        $balances = null;
        if ($firstLote) {
            $balances = Balances::where('lote_id', $firstLote->id)->first();
        }


        if($cantidad_de_lotes === 1 && $firstLote) { // ensure $firstLote is not null
            $lote = $firstLote; // Use the already fetched $firstLote
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

            // Calculate sum of cargos adicionales
            $cargos_adicionales_list = CargoAdicional::where('user_id', $user->id)->get();
            $sum_cargos_adicionales = $cargos_adicionales_list->sum('total');

            // Get pagos for these cargos adicionales
            $cargo_ids = $cargos_adicionales_list->pluck('id');
            $pagos_cargos_adicionales_list = PagoCargoAdicional::with('cargoAdicional') // Eager load cargoAdicional
                                                                ->whereIn('cargo_adicional_id', $cargo_ids)
                                                                ->orderBy('created_at', 'desc') // Order by creation date
                                                                ->get();
            $sum_pagos_cargos_adicionales = $pagos_cargos_adicionales_list->sum('total');


            return view('dashboard', [
                'data' => [
                    'user' => $user,
                    'lote' => $lote,
                    'manzana' => $lote->manzana,
                    'balance' => $balance,
                    'promesa' => $promesa,
                    'credito' => $credito,
                    'pagos' => $pagos,
                    'balance_de_pagos_realizados' => $balance_de_pagos_realizados,
                    'balance_pendiente_por_pagar' => $balance_pendiente_por_pagar,
                    'balance_a_credito' => $balance_a_credito,
                    'balance_pagado' => $amount_paid,
                    'porcentaje_por_pagar' => $porcentaje_por_pagar,
                    'rows' => $lote->balances->plan_de_pagos ?? count($pagos),
                    'pago_por_mes' => $payment_per_month,
                    'fecha_de_pago_promesa' => $fecha_de_pago_promesa,
                    'interes' => $interes,
                    'pago_mensual' => $pago_mensual,
                    'balance_id' => $balances->id ?? null,
                    'tiene_deuda' => $lote->balances->tiene_deuda ?? null,
                    'isCuentaMadre' => true,
                    'sum_cargos_adicionales' => $sum_cargos_adicionales ?? 0,
                    'cargos_adicionales_list' => $cargos_adicionales_list,
                    'pagos_cargos_adicionales_list' => $pagos_cargos_adicionales_list,
                    'sum_pagos_cargos_adicionales' => $sum_pagos_cargos_adicionales ?? 0,
                ]
            ]);
        }

        if ($cantidad_de_lotes > 1) {
            $lote = $user->lotes;
            // Cargos adicionales data is not passed to dashboard-multiple for now
            return view('dashboard-multiple', [
                'data' => [
                    'user' => $user,
                    'lotes' => $lote,
                    'isCuentaMadre' => true,
                ]
            ]);
        }

        // Fallback for no lotes in cuentaMadreDashboard
        $cargos_adicionales_list = collect();
        $sum_cargos_adicionales = 0;
        $pagos_cargos_adicionales_list = collect();
        $sum_pagos_cargos_adicionales = 0;

        return view('dashboard', [
            'data' => [
                'user' => $user,
                'lote' => null,
                'isCuentaMadre' => true,
                'sum_cargos_adicionales' => $sum_cargos_adicionales,
                'cargos_adicionales_list' => $cargos_adicionales_list,
                'pagos_cargos_adicionales_list' => $pagos_cargos_adicionales_list,
                'sum_pagos_cargos_adicionales' => $sum_pagos_cargos_adicionales,
            ]
        ]);
    }

}
