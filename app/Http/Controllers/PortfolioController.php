<?php

namespace App\Http\Controllers;

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

    public function store(Request $request)
    {
        $portfolioData = $request->validate([
            'name' => 'required|string|max:255',
            'account_id' => 'required|int',
            'currency' => 'required|string|size:3',
        ]);

        $brokerConnectionsData = $request->validate([
            'name' => 'required|string|max:255',
            'broker_type' => 'required|string',
            'api_token' => 'nullable|string',
        ]);

        $request->user()->portfolios()->create($portfolioData);
        $request->user()->brokerConnections()->create($brokerConnectionsData);

        return Redirect::back()->with('message', 'Портфель успешно добавлен!');
    }

}
