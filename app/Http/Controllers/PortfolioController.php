<?php

namespace App\Http\Controllers;

use App\Enums\BrokersEnum;
use App\Http\Resources\PortfolioResource;
use App\Models\Portfolio;
use App\Services\Brokers\BrokerClientResolver;
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
            'account_id' => 'required|string|max:255',
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

    public function show(Request $request, Portfolio $portfolio, PortfolioService $portfolioService)
    {
        if(!$portfolioService->checkUserPortfolioId(
            $request->user(),
            $portfolio
        )) {
            return Redirect::back();
        }

        $portfolio->load('positions.asset');

        return Inertia::render('Portfolio/Show', [
            'portfolio' => new PortfolioResource($portfolio)
        ]);
    }

    public function sync(
        Request $request,
        Portfolio $portfolio,
        PortfolioService $portfolioService
    ) {
        $portfolioService->syncPortfolio($request->user(), $portfolio->id);

        return Redirect::route('portfolios.show', $portfolio);
    }

    public function test(Request $request, BrokerClientResolver $brokerClientResolver)
    {

        $portfolio = auth()->user()->portfolios()->find(1);
        $brokerConnection = $portfolio->brokerConnection;
        $brokerClient = $brokerClientResolver->resolve($brokerConnection->broker_type);
        $assets = $brokerClient->getAssets(
            $brokerConnection->api_token,
            'INSTRUMENT_TYPE_BOND'
        );

        dd($assets);

    }

}
