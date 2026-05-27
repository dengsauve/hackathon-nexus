<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('home', [
            'upcomingEvents' => Event::query()
                ->publiclyDiscoverable()
                ->orderBy('starts_at')
                ->limit(3)
                ->get(),
        ]);
    }
}
