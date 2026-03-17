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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'broker_type' => 'required|string',
            'api_token' => 'nullable|string',
            'currency' => 'required|string|size:3',
        ]);

        // Создаем портфель через связь с текущим пользователем
        $request->user()->portfolios()->create($validated);

        return Redirect::back()->with('message', 'Портфель успешно добавлен!');
    }

}
