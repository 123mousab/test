<?php

namespace Src\Trader\Application\Http\Controllers;

use App\Http\Controllers\Controller;
use Src\Trader\Application\Http\Requests\TraderRequest;
use Src\Trader\Contracts\TraderContract;

class TraderController extends Controller
{
    private TraderContract $traderService;

    public function __construct(TraderContract $traderService)
    {
        $this->traderService = $traderService;
    }

    public function index()
    {
        $this->traderService->index();

        return response()->json([
            'message' => 'get successfully'
        ]);
    }

    public function store(TraderRequest $request)
    {
        $request->validated();

        $this->traderService->store($request->all());

        return response()->json([
            'message' => 'saved successfully'
        ], 201);
    }

    public function update($id, TraderRequest $request)
    {
        $request->validated();

        $this->traderService->update($id, $request->all());

        return response()->json([
            'message' => 'update successfully'
        ]);
    }
}
