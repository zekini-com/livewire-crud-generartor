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

class {{ucfirst($modelBaseName)}}Table extends LivewireDatatable
{ 

    use HandlesFile, AuthorizesRequests;


    public function builder()
    {
        return {{ucfirst($modelBaseName)}}::query()
        @foreach($relations as $relation)
            ->leftJoin('{{$relation['table']}}', '{{$relation['table']}}.id', "{{strtolower(Str::plural($modelBaseName))}}.{{$relation['column']}}")
        @endforeach
        ->groupBy('{{strtolower(Str::plural($modelBaseName))}}.id');
        
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
                        ->filterable()
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
                    Column::name('{{$col['name']}}')
                        ->label('{{ucfirst($col['name'])}}')
                        ->defaultSort('asc')
                        ->searchable()
                        ->hideable()
                        ->filterable(),
                    @break
                @endswitch

            @endforeach
        ];
    }


   
}
