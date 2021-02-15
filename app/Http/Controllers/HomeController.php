<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Scopes\UserScope;

class HomeController extends Controller
{
    public function index()
    {
        $query = Event::query();
        $query->withoutGlobalScope(UserScope::class);

        if ($date = request()->get('date')) {
            $query->whereDate('moment', $date);

            if ($time = request()->get('time')) {
                $query->whereTime('moment', $time . ':00');
            }
        }

        if ($location = request()->get('location')) {
            $query->where('location', 'LIKE', "%{$location}%");
        }

        return $query->paginate(10);
    }
}
