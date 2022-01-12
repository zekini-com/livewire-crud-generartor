<?php

namespace App\Http\Controllers\Admin;

use Illuminate\View\View;
use OwenIt\Auditing\Models\Audit;
use App\Http\Controllers\Controller; /** @phpstan-ignore-line */
use Illuminate\Foundation\Inspiring;
use Illuminate\Contracts\View\Factory;
/**
 * @psalm-suppress UndefinedClass
 */
class AdminHomepageController extends Controller /** @phpstan-ignore-line */
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
