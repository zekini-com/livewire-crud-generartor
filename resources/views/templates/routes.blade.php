
/* Auto-generated admin routes */
Route::middleware(['auth:' . config('zekini-admin.defaults.guard')])->group(static function () {
    Route::prefix('admin')->name('admin/')->group(static function() {
        Route::prefix('{{ $resource }}')->name('{{ $resource }}/')->group(static function() {
            Route::get('/', ['App\Http\Controllers\Admin\{{ucfirst($resource)}}Controller', 'index'])->name('index');
            Route::get('/create', ['App\Http\Livewire\Create{{ucfirst($resource)}}', '__invoke'])->name('create');
            {!! "Route::get('/{".$modelVariableName."}/edit'" !!}, ['App\Http\Livewire\Edit{{ ucfirst($resource)}}', '__invoke'])->name('edit');
        });
    });
});