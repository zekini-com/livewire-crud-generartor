@php echo "<?php";
@endphp


namespace {{ $controllerNamespace }};

use App\Http\Controllers\Controller;

use {{ $modelFullName }};
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;

use Illuminate\View\View;

class {{ $controllerBaseName }} extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * {{'@'}}param Index{{ $modelBaseName }} $request
     * {{'@'}}return array|Factory|View
     */
    public function index()
    {
        $this->authorize('admin.{{$modelDotNotation}}.index');
        
        return view('{{$viewName}}');
    }

    

}
