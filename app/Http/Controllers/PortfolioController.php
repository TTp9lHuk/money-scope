<?php

namespace App\Http\Controllers;

use App\Services\PortfolioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class PortfolioController extends Controller
{
    public function index()
    {
        return Inertia::render('Portfolio/Index', [
            'portfolios' => auth()->user()->portfolios ?? []
        ]);
    }

    public function store(Request $request, PortfolioService $portfolioService)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'account_id' => 'required|int',
            'currency' => 'required|string|size:3',
            'broker_type' => 'required|string',
            'api_token' => 'nullable|string',
        ]);

        $result = $portfolioService->createPortfolioWithBroker(
            $request->user(),
            $validated
        );

        return Redirect::back()
            ->with('message', 'Портфель успешно добавлен!');
    }

}
