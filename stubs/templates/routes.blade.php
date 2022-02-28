

/* Auto-generated admin routes */
Route::middleware(['auth', 'role:' . admin_roles()])->group(static function () {
    Route::prefix('admin')->name('admin/')->group(static function() {
        Route::prefix('{{ $resource }}')->name('{{ $resource }}/')->group(static function() {
            Route::get('/', App\Http\Livewire\{{$livewireFolderName}}\{{$livewireFolderName}}::class)->name('index');
        });
    });
});