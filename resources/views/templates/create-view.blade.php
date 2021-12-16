@php
$openBlade = '{{';
$closeBlade = '}}';
@endphp

        <div class="row">
            <div class="col">
                <div class="card">
            
                    <div class="card-body">
                        <div class="card-block">
                            <form wire:submit.prevent="create">
                                {{'@'}}if ($success)
                                    <div>
                                        <div class="alert alert-success">
                                            {{$modelVariableName}} added successfully <a href="#">View all {{$modelVariableName}}s</a>
                                        </div>

                                    </div>
                                {{'@'}}endif

                               {{'@'}}include('admin.{{$modelVariableName}}.components.form')
                               <div class="form-group row align-items-center mt-3" >
                                    <div class="col-xl-8">
                                        <button class="btn btn-primary" type="submit">Create {{$modelVariableName}}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
