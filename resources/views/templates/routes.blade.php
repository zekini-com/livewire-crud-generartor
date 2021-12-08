
/* Auto-generated admin routes */
Route::middleware(['auth:' . config('zekini-admin.defaults.guard')])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function() {
        Route::prefix('{{ $resource }}')->name('{{ $resource }}/')->group(static function() {
            Route::get('/','{{ $resourceController }}@index')->name('index');
            Route::get('/create','{{ $resourceController }}@create')->name('create');
            Route::post('/','{{ $resourceController }}@store')->name('store');
            {!! "Route::get('/{".$modelVariableName."}/edit'" !!},'{{ $resourceController }}@edit')->name('edit');

            {!! "Route::post('/{".$modelVariableName."}'" !!},'{{ $resourceController }}@update')->name('update');
            {!! "Route::delete('/{".$modelVariableName."}'" !!},'{{ $resourceController }}@destroy')->name('destroy');
        });
    });
});