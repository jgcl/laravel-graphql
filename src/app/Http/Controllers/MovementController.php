<?php

namespace App\Http\Controllers;

use App\Services\MovementService;
use Illuminate\Http\Request;

class MovementController extends Controller
{
    private $movementService;

    public function __construct(MovementService $movementService)
    {
        $this->movementService = $movementService;
    }

    public function processRequest(Request $request)
    {
        $request->validate([
            'mutation' => 'required|in:saldo,depositar,sacar'
        ]);

        switch ($request->mutation) {
            case 'saldo':
                return $this->balance($request);

            case 'sacar':
                return $this->withdraw($request);

            case 'depositar':
                return $this->deposit($request);
        }

    }

    public function balance(Request $request) {
        $request->validate([
            'conta' => 'required|integer|min:1'
        ]);

        $movement = $this->movementService->balance($request->conta);

        return [
            'data' => [
                'saldo' => [
                    'conta' => $movement->account,
                    'saldo' => $movement->balance
                ]
            ]
        ];
    }

    public function withdraw(Request $request) {
        $request->validate([
            'conta' => 'required|integer|min:1',
            'valor' => 'required|numeric|min:0',
        ]);

        $movement = $this->movementService->withdraw($request->conta, $request->valor);

        return [
            'data' => [
                'sacar' => [
                    'conta' => $movement->account,
                    'saldo' => $movement->balance
                ]
            ]
        ];
    }

    public function deposit(Request $request) {
        $request->validate([
            'conta' => 'required|integer|min:1',
            'valor' => 'required|numeric|min:0',
        ]);

        $movement = $this->movementService->deposit($request->conta, $request->valor);

        return [
            'data' => [
                'depositar' => [
                    'conta' => $movement->account,
                    'saldo' => $movement->balance
                ]
            ]
        ];
    }
}
