<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class ChirpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $product = Product::findOrFail($productId);
        
        $chirps = $product->chirps()->with('user')->get();

        return view('login.Product-info' , compact('chirps','product'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:255'],
        ]);
    
        info($validated);
        info($request->user());
    
        $request->user()->chirps()->create($validated);
    
        // 使用已定義的路由名稱進行重定向
        return redirect()->route('products.info');
    }
    

    /**
     * Display the specified resource.
     */
    public function show(Chirp $chirp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chirp $chirp): View
    {
        //
        Gate::authorize('update', $chirp);
 
        return view('chirps.edit', [
            'chirp' => $chirp,
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chirp $chirp): RedirectResponse
    {
        //
        Gate::authorize('update', $chirp);
 
        $validated = $request->validate([
            'message' => ['required' , 'string' , 'max:255'],
        ]);
 
        $chirp->update($validated);
 
        return redirect(route('products.info'));
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chirp $chirp): RedirectResponse
    {
        //
        Gate::authorize('delete', $chirp);
 
        $chirp->delete();
 
        return redirect(route('products.info'));
    }
}
