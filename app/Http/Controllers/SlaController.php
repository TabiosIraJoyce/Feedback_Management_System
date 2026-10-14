<?php

namespace App\Http\Controllers;

use App\Models\SlaConfig;
use Illuminate\Http\Request;

class SlaController extends Controller
{
    public function index()
    {
        $configs = SlaConfig::orderBy('category')->get();

        if ($configs->isEmpty()) {
            // seed defaults
            SlaConfig::updateOrCreate(['category' => 'safety_concern'], ['hours' => 1, 'enabled' => true]);
            SlaConfig::updateOrCreate(['category' => 'fuel_quality'], ['hours' => 4, 'enabled' => true]);
            SlaConfig::updateOrCreate(['category' => 'service_concern'], ['hours' => 24, 'enabled' => true]);
            $configs = SlaConfig::orderBy('category')->get();
        }

        return view('sla.index', compact('configs'));
    }

    public function update(Request $request, SlaConfig $slaConfig)
    {
        $data = $request->validate([
            'hours' => 'required|integer|min:1',
            'enabled' => 'sometimes|boolean',
        ]);

        $slaConfig->update($data);

        return redirect()->back()->with('status', 'SLA updated');
    }
}
