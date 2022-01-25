@php echo "<?php";
@endphp

namespace App\Imports;

use App\Models\{{ucfirst($modelBaseName)}};
use Illuminate\Support\Facades\Validator;
use Livewire\TemporaryUploadedFile;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class {{Str::plural(ucfirst($modelBaseName))}}Import implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    use RemembersRowNumber;

    public function __construct(
        public TemporaryUploadedFile $file
    ) {
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        logger('Row: ' . $this->getRowNumber());

        Validator::make($row, [
            @foreach($vissibleColumns as $col)
                '*.{{$col['name']}}'=> 'required',
            @endforeach
        ])
            ->validate();

        @php $firstCol = $vissibleColumns->first(); @endphp
        return {{ucfirst($modelBaseName)}}::updateOrCreate(
            [
                '{{$firstCol['name']}}' => $row['{{$firstCol['name']}}'],
            ],
            [
                @foreach($vissibleColumns as $col)
                    @if($firstCol['name'] == $col['name'])
                        @continue
                    @endif
                    '{{$col['name']}}'=> $row['{{$col['name']}}'],
                @endforeach
            ]
        );
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
