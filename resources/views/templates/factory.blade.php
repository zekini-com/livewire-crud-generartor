@php echo "<?php";
@endphp

namespace {{ $factoryNamespace }};

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use {{$factoryModelNamespace}};

class {{$factoryBaseName}} extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [

            @foreach($fakerAttributes as $attribute)
                "{{$attribute['name']}}"=> {!! $attribute['faker'] !!},
            @endforeach
        ];
    }


}
