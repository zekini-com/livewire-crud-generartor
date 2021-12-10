@php
$openBlade = '{{';
$closeBlade = '}}';
@endphp
{{'@'}}section('body')

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Create new {{$modelVariableName}}
                    </div>
                    <div class="card-body">
                        <div class="card-block">
                            <form >
                               
                               {{'@'}}include('admin.{{$modelVariableName}}.components.form')
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
   

{{'@'}}endsection