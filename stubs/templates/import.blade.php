@php echo "<?php";
@endphp

namespace App\Imports;

use App\Models\{{ucfirst($modelBaseName)}};
use Illuminate\Support\Facades\DB;
use Livewire\TemporaryUploadedFile;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class {{Str::plural(ucfirst($modelBaseName))}}Import implements ToModel, WithHeadingRow, WithBatchInserts, WithValidation, WithChunkReading
{
    use RemembersRowNumber;

    public function __construct(
        public TemporaryUploadedFile $file
    ) {
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row): void
    {
        logger('Row: ' . $this->getRowNumber());

        @php $firstCol = $vissibleColumns->first(); @endphp
         DB::table('{{Str::plural(strtolower($modelBaseName))}}')->whereNull('deleted_at')->updateOrInsert(
            [
                '{{$firstCol['name']}}' => $row['{{$firstCol['name']}}'],
            ],
            [
                @foreach($vissibleColumns as $col)
                    '{{$col['name']}}'=> $row['{{$col['name']}}'],
                @endforeach
            ]
        );
    }

    public function rules(): array
    {
        return [
            @foreach($vissibleColumns as $col)
                '*.{{$col['name']}}'=> 'required',
            @endforeach
        ];
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
