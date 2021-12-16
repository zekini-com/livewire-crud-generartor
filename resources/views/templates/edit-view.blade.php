@php
$openBlade = '{{';
$closeBlade = '}}';
@endphp

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Edit
                    </div>
                    <div class="card-body">
                        <div class="card-block">
                            <form wire:submit.prevent="update">
                                {{'@'}}if ($success)
                                    <div>
                                        <div class="alert alert-success">
                                            {{$modelVariableName}} updated successfully <a href="#">View all {{$modelVariableName}}s</a>
                                        </div>

                                    </div>
                                {{'@'}}endif
                               
                               {{'@'}}include('admin.{{$modelVariableName}}.components.form')
                               <div class="form-group row align-items-center mt-3" >
                                    <div class="col-xl-8">
                                        <button class="w-32 shadow-sm inline-flex justify-center items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:border-red-700 focus:shadow-outline-teal active:bg-red-700 transition ease-in-out duration-150" type="submit">Update {{$modelVariableName}}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
   