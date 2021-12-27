@php echo "<?php";
@endphp

namespace App\Http\Livewire;

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

class List{{ucfirst($modelBaseName)}} extends LivewireDatatable
{ 

    use HandlesFile, AuthorizesRequests;
    
    /**
     * Checks to see if we can softdelete a model
    */
    @if ($canBeTrashed)
    protected $canBeTrashed = true;
    @else
    protected $canBeTrashed = false;
    @endif

    public $model = {{ucfirst($modelBaseName)}}::class;

    public $exportable = true;


    public function builder()
    {
        return {{ucfirst($modelBaseName)}}::query()
        @foreach($relations as $relation)
            @if(!empty($relation['pivot']) && isset($relation['pivot']))
    
            ->join('{{$relation['pivot']}}', '{{strtolower(Str::plural($modelDotNotation))}}.id', '=', "{{$relation['pivot']}}.{{$modelDotNotation == 'zekini_admin' ? 'model': strtolower(Str::singular($modelDotNotation))}}_id")
            ->join('{{$relation['table']}}', '{{$relation['pivot']}}.{{$relation['column']}}', '=', '{{$relation['table']}}.id')
            @else
            ->leftJoin('{{$relation['table']}}', '{{$relation['table']}}.id', "{{strtolower(Str::plural($modelDotNotation))}}.{{$relation['column']}}")
            @endif
        @endforeach
       
        ->groupBy('{{strtolower(Str::snake(Str::plural($modelBaseName)))}}.id');
        
    }

    public function columns()
    {
        return [

            @foreach($vissibleColumns as $col)

                @if(Str::isRelation($col['name']))
                @php
                    $relationTable = Str::plural(Str::relationName($col['name']));
                @endphp
                Column::name('{{$relationTable}}.{{$tableTitleMap[$relationTable]}}')
                        ->label('{{ucfirst(Str::relationName($col['name']))}}')
                        ->searchable()
                        ->hideable()
                        ->filterable(),
                @continue
                @endif

                @switch($col['type'])
                    @case('integer')
                    NumberColumn::name('{{$col['name']}}')
                        ->label('{{ucfirst($col['name'])}}'),
                    @break
                    @case('boolean')
                    BooleanColumn::name('{{$col['name']}}')
                    ->label('{{ucfirst($col['name'])}}')
                    ->format()
                    ->filterable(),
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
                        ->searchable()
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


            Column::callback(['id'], function ($id) {
                return view('zekini/livewire-crud-generator::datatable.table-actions', [
                    'id' => $id, 
                    'view' => '{{strtolower(Str::kebab($modelBaseName))}}',
                    'model'=> '{{strtolower($modelBaseName)}}',
                    'canBeTrashed'=> $this->canBeTrashed
                ]);
            })->unsortable()->excludeFromExport()
        ];
    }

    @if($canBeTrashed)
    /**
     * Force deletes a model
     *
     * @param  {{ucfirst($modelBaseName)}} ${{$modelBaseName}}
     * @return void
     */
    public function forceDelete({{ucfirst($modelBaseName)}} ${{strtolower($modelBaseName)}})
    {

        $this->authorize('admin.{{strtolower($modelDotNotation)}}.delete');

        $fileCols = $this->checkForFiles(${{strtolower($modelBaseName)}});
        foreach($fileCols as $files){
            $this->deleteFile($files);
        }

        ${{strtolower($modelBaseName)}}->forceDelete();
    }
    @endif

    
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

    /**
     * Force deletes a model
     *
     * @param  {{ucfirst($modelBaseName)}} ${{$modelBaseName}}
     * @return void
     */
    public function delete($id)
    {
        ${{strtolower($modelBaseName)}} = {{ucfirst($modelBaseName)}}::find($id);
        $this->authorize("admin.{{strtolower($modelDotNotation)}}.delete");

        $fileCols = $this->checkForFiles(${{strtolower($modelBaseName)}});
        foreach($fileCols as $files){
            $this->deleteFile($files);
        }

        ${{strtolower($modelBaseName)}}->delete();
    }


   
}
