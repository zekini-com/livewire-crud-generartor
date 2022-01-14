
@php($resourcePlural = Str::plural(ucfirst($resource)))
/* Auto-generated admin routes */
Route::middleware(['auth:' . config('zekini-admin.defaults.guard')])->group(static function () {
    Route::prefix('admin')->name('admin/')->group(static function() {
        Route::prefix('{{ $resource }}')->name('{{ $resource }}/')->group(static function() {
            Route::get('/', App\Http\Livewire\{{$resourcePlural}}\{{$resourcePlural}}::class)->name('index');
        });
    });
});