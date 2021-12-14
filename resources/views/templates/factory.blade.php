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
            @if($attribute['name'] == 'file' || $attribute['name']== 'image')
                "{{$attribute['name']}}" => '["img.jpg"]',
            @else
            "{{$attribute['name']}}"=> {!! $attribute['faker'] !!},
            @endif
            @endforeach
        ];
    }


}
