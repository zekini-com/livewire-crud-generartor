@php echo "<?php";
@endphp

namespace App\Http\Livewire\{{Str::plural(ucfirst($modelBaseName))}}\Datatable;

use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Zekini\CrudGenerator\Traits\HandlesFile;
use Zekini\CrudGenerator\Helpers\CrudModelList;
use {{ $modelFullName }};
use Illuminate\Support\Str;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\BooleanColumn;

class {{Str::plural(ucfirst($modelBaseName))}}Table extends LivewireDatatable
{ 

    use HandlesFile, AuthorizesRequests;


    public $model = {{ucfirst($modelBaseName)}}::class;

    public $exportable = true;

    public $softdeletes = false;

   
    @if($isReadonly)
    public $showBtns  = false;
    @else
    public $showBtns = true;
    @endif
 

    public $launchCreateEventModal = 'launch{{ucfirst($modelBaseName)}}CreateModal';

    protected $customListeners = [
        'toggleSoftDeletes'
    ];


    public function builder()
    {
        $query =  {{ucfirst($modelBaseName)}}::query();

        $query = $this->softdeletes ? $query->onlyTrashed() : $query;

        return $query

        @if(count($relations) > 0)
        @foreach($relations as $relation)
            @if(!empty($relation['pivot']) && isset($relation['pivot']))
    
            ->join('{{$relation['pivot']}}', '{{strtolower(Str::plural($modelDotNotation))}}.id', '=', "{{$relation['pivot']}}.{{$modelDotNotation == 'zekini_admin' ? 'model': strtolower(Str::singular($modelDotNotation))}}_id")
            ->join('{{$relation['table']}}', '{{$relation['pivot']}}.{{$relation['column']}}', '=', '{{$relation['table']}}.id')
            @else
            ->leftJoin('{{$relation['table']}}', '{{$relation['table']}}.id', "{{strtolower(Str::plural($modelDotNotation))}}.{{$relation['column']}}")
            @endif
        @endforeach
        @else
        ->groupBy('{{strtolower(Str::snake(Str::plural($modelBaseName)))}}.id')
        @endif
       
       ;
        
    }

    public function columns()
    {
        return [

           

            @foreach($vissibleColumns as $col)

                @if(Str::isRelation($col['name']))
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
                Column::name('{{Str::singular($pivot['table'])}}.{{$tableTitleMap[$pivot['table']]}}')
                ->label('{{Str::singular($pivot['table'])}}')
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
     * @param  $id
     * @return void
     */
    public function forceDelete($id)
    {
        $this->authorize('admin.{{strtolower($modelDotNotation)}}.delete');

        ${{strtolower($modelBaseName)}} = {{ucfirst($modelBaseName)}}::withTrashed()->find($id);

        $fileCols = $this->checkForFiles(${{strtolower($modelBaseName)}});
        foreach($fileCols as $files){
            $this->deleteFile($files);
        }

        ${{strtolower($modelBaseName)}}->forceDelete();

        $this->emit('refreshLivewireDatatable');
    }

    /**
     * Restores a deleted model
     *
     * @param  $id
     * @return void
     */
    public function restore($id)
    {
        $this->authorize('admin.{{strtolower($modelDotNotation)}}.delete');

        ${{strtolower($modelBaseName)}} = {{ucfirst($modelBaseName)}}::withTrashed()->find($id);

        $fileCols = $this->checkForFiles(${{strtolower($modelBaseName)}});
        foreach($fileCols as $files){
            $this->deleteFile($files);
        }

        ${{strtolower($modelBaseName)}}->restore();

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
        })->toArray();
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


   
}
