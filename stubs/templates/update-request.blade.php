@php echo "<?php"
@endphp


namespace App\Http\Requests\Admin\{{ $modelBaseName }};

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;


class Update{{ $modelBaseName }} extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * {{'@'}}return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.{{ $modelDotNotation }}.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * {{'@'}}return array
     */
    public function rules(): array
    {
        return [

        ];
    }

    /**
     * Gets the rule set needed for validation
     *
     * {{'@'}}return array
     */
    public function getRuleSet()
    {
        return [
        @foreach($vissibleColumns as $col)
        '{{$col['name']}}'=> 'required',
        @endforeach
        ];
    }

    /**
    * Modify input data
    *
    * {{'@'}}return array
    */
    public function getSanitized(): array
    {
        $sanitized = $this->validated();

        //Add your code for manipulation with request data here

        return $sanitized;
    }
}