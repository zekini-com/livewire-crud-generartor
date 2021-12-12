<?php

namespace Zekini\CrudGenerator\Http\Controllers;

use Illuminate\View\View;
use OwenIt\Auditing\Models\Audit;
use Illuminate\Foundation\Inspiring;
use Illuminate\Contracts\View\Factory;

class AdminHomepageController extends Controller
{
    /**
     * Display default admin home page
     *
     * @return Factory|View
     */
    public function index()
    {
        return view('zekini/livewire-crud-generator::admin.homepage.index', [
            'inspiration' => Inspiring::quote()
        ]);
    }

    
    /**
     * Audit Logs
     *
     * @return View
     */
    public function audit()
    {
        return view('zekini/livewire-crud-generator::admin.homepage.audit', [
            'logs' => Audit::all()
        ]);
    }
}
