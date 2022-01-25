@php echo "<?php";
@endphp

namespace App\Http\Livewire\{{Str::plural(ucfirst($modelBaseName))}}\Datatable;

use App\Imports\{{Str::plural(ucfirst($modelBaseName))}}Import;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Zekini\CrudGenerator\Traits\HandlesFile;
use Zekini\CrudGenerator\Helpers\CrudModelList;
use {{ $modelFullName }};
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\BooleanColumn;

@php $isActivityLogModel = ucfirst($modelBaseName) == 'ActivityLog'; @endphp


class {{Str::plural(ucfirst($modelBaseName))}}Table extends LivewireDatatable
{
    use AuthorizesRequests;
    use HandlesFile;
    use WithFileUploads;

    public $model = {{ucfirst($modelBaseName)}}::class;

    public $beforeTableSlot = 'partials.import-button';

    public $exportable = true;

    public $file;

    public $softdeletes = false;

    @if($isReadonly)
    public $showBtns  = false;
    @else
    public $showBtns = true;
    @endif

    public $launchCreateEventModal = 'launch{{ucfirst($modelBaseName)}}CreateModal';

    protected $customListeners = [
        'downloadTemplate',
        'toggleSoftDeletes',
    ];

    public function builder()
    {
        $query =  {{ucfirst($modelBaseName)}}::query();

        $query = $this->softdeletes ? $query->onlyTrashed() : $query; 

        return $query

        @if($isActivityLogModel)

            ->with(['causer', 'subject'])

        @else

        @if(count($relations) > 0)
        ->with([
        @foreach($relations as $relation)
            @if($loop->last)
           '{{Str::getRelationship($relation)}}'
           @else
           '{{Str::getRelationship($relation)}}',
           @endif
        @endforeach
        ])
        @else
        ->groupBy('{{strtolower(Str::snake(Str::plural($modelBaseName)))}}.id')
        @endif

        @endif
       
       ;
        
    }

    public function columns()
    {
        return [

           
            // adding causer and subject for logs
            @if($isActivityLogModel)
            Column::callback(['id', 'causer_type'], function($id, $causer_type){
                $causer = {{ucfirst($modelBaseName)}}::withTrashed()->findOrFail($id)->causer;
                $explode = explode("\\", $causer_type);
                $type = $explode[array_key_last($explode)];
                return "$causer->name ($type)";
            })->label('Causer')
                ,
            @endif

            @foreach($vissibleColumns as $col)

                
                @if(in_array($col['name'], ['causer_type']) && $isActivityLogModel)
                // for activitylog polymorphic relations we skip so we handle differently
                    @continue
                @endif

                
                @if(Str::isRelation($col['name']))
                // checks if the column name is a relation  eg table_id
                    @php
                        $relationTable = Str::plural(Str::relationName($col['name']));
                    @endphp
                    @if(in_array($relationTable, $tableTitleMap))
                        Column::name('{{$relationTable}}.{{$tableTitleMap[$relationTable]}}')
                                ->label('{{ucfirst(Str::relationName($col['name']))}}')
                                ->hideable()
                                ->filterable(),
                    @endif
                    @continue
                @endif

                @switch($col['type'])
                    @case('integer')
                    NumberColumn::name('{{$col['name']}}')
                        ->label('{{ucfirst($col['name'])}}'),
                    @break
                    @case('boolean')
                    BooleanColumn::name('{{$col['name']}}')
                    ->label('{{ucfirst($col['name'])}}'),
                    @break
                    @case('date')
                    @case('datetime')
                    DateColumn::name('{{$col['name']}}')
                    ->label('{{ucfirst($col['name'])}}')
                    ->filterable()
                    ->hide(),
                    @break
                    @case('string')
                    @default
                    @if(Str::likelyFile($col['name']))
                        Column::callback(['{{$col['name']}}'], function (${{$col['name']}}) {
                            return view('zekini/livewire-crud-generator::datatable.image-display', ['file' => ${{$col['name']}}]);
                        })->unsortable()->excludeFromExport(),
                    @else
                    
                        Column::name('{{$col['name']}}')
                            ->label('{{ucfirst($col['name'])}}')
                            ->defaultSort('asc')
                            @if($col['name'] == $vissibleColumns->first()['name'])
                            ->searchable()
                            @endif
                            ->hideable()
                            ->filterable(),
                    @endif
                    @break
                @endswitch

            @endforeach

           

            // belongs to many relationship tables
            @foreach($pivots as $pivot)
                Column::name('{{$pivot['table']}}.{{$tableTitleMap[$pivot['table']]}}')
                ->label('{{Str::singular($pivot['table'])}}')
            ,
            @endforeach

            // Non pivot belongs to relationships
            @foreach($nonPivotBelongsTo as $npRelation)
                Column::name('{{Str::getRelationship($npRelation)}}.{{$tableTitleMap[$npRelation['table']]}}')
                ->label('{{Str::singular($npRelation['table'])}}')
            ,
            @endforeach

            @if(! $isReadonly)
            Column::callback(['id'], function ($id) {
                return view('zekini/livewire-crud-generator::datatable.table-actions', [
                    'id' => $id, 
                    'view' => '{{strtolower(Str::kebab($modelBaseName))}}',
                    'model'=> '{{Str::camel($modelBaseName)}}',
                    'softdeletes'=> $this->softdeletes
                ]);
            })->label('Actions')->excludeFromExport()
            @endif
        ];
    }

    protected function getListeners()
    {
        return array_merge($this->listeners, $this->customListeners);
    }

    /**
     * Force deletes a model
     *
     * @param  int $id
     * @return void
     */
    public function forceDelete(int $id): void
    {
        $this->authorize('admin.{{strtolower($modelDotNotation)}}.delete');

        ${{strtolower($modelBaseName)}} = {{ucfirst($modelBaseName)}}::withTrashed()->find($id);/** @phpstan-ignore-line */

        $fileCols = $this->checkForFiles(${{strtolower($modelBaseName)}});
        foreach($fileCols as $files){
            $this->deleteFile($files);
        }

        ${{strtolower($modelBaseName)}}->forceDelete();

        $this->emit('flashMessageEvent', 'Item Deleted succesfully');

        $this->emit('refreshLivewireDatatable');
    }

    /**
     * Deletes  a model
     *
     * @param int $id
     * @return void
     */
    public function delete($id): void
    {
        $this->authorize('admin.{{strtolower($modelDotNotation)}}.delete');

        ${{strtolower($modelBaseName)}} = {{ucfirst($modelBaseName)}}::find($id);

        $fileCols = $this->checkForFiles(${{strtolower($modelBaseName)}});
        foreach($fileCols as $files){
            $this->deleteFile($files);
        }

        ${{strtolower($modelBaseName)}}->delete();

        $this->emit('flashMessageEvent', 'Item Trashed succesfully');

        $this->emit('refreshLivewireDatatable');
    }

    /**
     * Restores a deleted model
     *
     * @param  int $id
     * @return void
     */
    public function restore(int $id): void
    {
        $this->authorize('admin.{{strtolower($modelDotNotation)}}.delete');

        ${{strtolower($modelBaseName)}} = {{ucfirst($modelBaseName)}}::withTrashed()->find($id);

        $fileCols = $this->checkForFiles(${{strtolower($modelBaseName)}});
        foreach($fileCols as $files){
            $this->deleteFile($files);
        }

        ${{strtolower($modelBaseName)}}->restore();

        $this->emit('flashMessageEvent', 'Item Restored succesfully');

        $this->emit('refreshLivewireDatatable');
    }

    /**
     * Checks if a model has files or images and deletes it
     *
     * @param  mixed $model
     * @return array
     */
    protected function checkForFiles($model)
    {
        return collect($model->getAttributes())->filter(function($col, $index){
            return Str::likelyFile($index);
        })
            ->toArray();
    }

    public function launch{{ucfirst($modelBaseName)}}EditModal({{ucfirst($modelBaseName)}} ${{$modelBaseName}})
    {
        $this->emit('launch{{ucfirst($modelBaseName)}}EditModal', ${{$modelBaseName}});
    }

    public function toggleSoftDeletes()
    {
        $this->softdeletes = ! $this->softdeletes;

        $this->emit('refreshLivewireDatatable');
    }

    public function render()
    {
        $this->emit('refreshDynamic');

        if ($this->persistPerPage) {
            session()->put([$this->sessionStorageKey() . '_perpage' => $this->perPage]);
        }

        return view('zekini/livewire-crud-generator::datatable.datatable')->layoutData(['title' => $this->title]);
    }

    public function downloadTemplate()
    {
        $filename = '{{strtolower($modelBaseName)}}.xlsx';

        if (!Storage::disk('templates')->exists($filename)) {
            $errorMessage = 'Failed to find template ' . $filename;
            $this->emit('flashMessageEvent', $errorMessage);
            Log::error($errorMessage);
            return;
        }

        return response()->download(storage_path('app/public/templates/' . $filename));
    }

    public function updatedFile()
    {
        $filename = $this->file->store('imports');

        Excel::import(new ClientsImport($this->file), $filename);

        $this->emit('flashMessageEvent', 'Imported');
        $this->emit('refreshLivewireDatatable');
    }
}
