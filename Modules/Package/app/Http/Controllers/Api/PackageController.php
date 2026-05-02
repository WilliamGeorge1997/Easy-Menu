<?php

namespace Modules\Package\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Package\Models\Package;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        $packages = Package::query()
            ->latest()
            ->get()
            ->map(fn (Package $p) => [
                'id' => $p->id,
                'title' => $p->getTranslation('title', $locale),
                'description' => $p->getTranslation('description', $locale),
                'price' => (float) $p->price,
                'discounted_price' => $p->discounted_price !== null ? (float) $p->discounted_price : null,
                'months' => (int) $p->months,
                'is_active' => (bool) $p->is_active,
            ]);

        return response()->json(['data' => $packages]);
    }
}

