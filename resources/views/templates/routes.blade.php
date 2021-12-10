
/* Auto-generated admin routes */
Route::middleware(['auth:' . config('zekini-admin.defaults.guard')])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Livewire')->name('admin/')->group(static function() {
        Route::prefix('{{ $resource }}')->name('{{ $resource }}/')->group(static function() {
            Route::get('/', ['List{{$resource}}', '__invoke'])->name('index');
            Route::get('/create', ['Create{{$resource}}', '__invoke'])->name('create');
            {!! "Route::get('/{".$modelVariableName."}/edit'" !!}, ['Edit{{ $resource}}', '__invoke'])->name('edit');
        });
    });
});