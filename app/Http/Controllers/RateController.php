<?php

namespace App\Http\Controllers;

use App\Http\Requests\RateRequest;
use App\Models\Currency;
use App\Models\Rate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $todayCurrencies = Currency::with('rate')->get();
        $currencies = Currency::all();
        // Get previous days rates
        $rates = Rate::where('date', '!=', date('Y-m-d'))->latest()->get()->groupBy('date');

        return view('rates.index', compact('todayCurrencies', 'currencies', 'rates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $currencies = Currency::all();
        return view('rates.create', compact('currencies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(RateRequest $request)
    {
        $currency = Currency::findOrFail($request->get('currency_id'));
        // Checking if the rate already have value for today
        // If yes, getting object to update it, if not just filling new one
        $rate = Rate::where([
            'currency_id' => $request->get('currency_id'),
            'date' => $request->get('date')
        ])->firstOrNew();
        $rate = $rate->fill($request->only('value', 'date'));
        $rate->currency()->associate($currency);
        $rate->save();
        return redirect()->route('rates.index')->withStatus('Rate saved successfully');
    }
}
