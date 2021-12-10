@php
$dataCountIf = '@if(count($data) > 0)';
$dataCountEndIf  = '@endif';
$dataCountElse = '@else';
$dataCountForeach = '@foreach($data as $item)';
$endDataCountForeach = '@endforeach';
$openBlade = '{{';
$closeBlade = '}}';
@endphp
{{'@'}}section('body')

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Index {{ $resource }} 
                        <button class="btn btn-primary">
                            <a href="{!! $openBlade !!} {!!$createResourceRoute!!} {!! $closeBlade !!}"> Create {{$resource}}</a>
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="card-block">
                            <form @submit.prevent="">
                                <div class="row justify-content-md-between">
                                    <div class="col col-lg-7 col-xl-5 form-group">
                                        <div class="input-group">
                                            <input class="form-control" placeholder="Search {{$resource}}" v-model="search" @keyup.enter="filter('search', $event.target.value)" />
                                            <span class="input-group-append">
                                                <button type="button" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp; Search</button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-auto form-group ">
                                        <select class="form-control" v-model="pagination.state.per_page">
                                            
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="100">100</option>
                                        </select>
                                    </div>
                                </div>
                            </form>


                            {!! $dataCountIf !!}

                                <table class="table table-hover table-listing">
                                    <thead>
                                        <tr>
                                            <th>S/N</th>
                                            @foreach($vissibleColumns as $col)
                                                <th>{{$col['name']}}</th>
                                            @endforeach
                                            <th>Actions</th>
                                        </tr>

                                    </thead>
                                    <tbody>
                                         {{$dataCountForeach}}
                                            <tr>
                                                <td>{!!$openBlade!!} $loop->index+1 {!! $closeBlade !!}</td>
                                                @foreach($vissibleColumns as $col)
                                                    
                                                   <td>
                                                       {!!$openBlade!!} $item->{{$col['name']}} {!! $closeBlade !!}
                                                   </td>
                                                @endforeach
                                                <td>
                                                    <span>
                                                        @php
                                                            $id = '$item->id';
                                                            $url = 'url("admin/'.$resource.'/".$item->id."/edit")';
                                                        @endphp
                                                     
                                                        <a href="{!! $openBlade !!} {!! $url !!} {!! $closeBlade !!}">Edit</a>
                                                        <a href="#">Delete</a>
                                                    </span>
                                                </td>
                                            </tr>
                                        {{$endDataCountForeach}} 
                                    </tbody>
                                </table>

                            {{$dataCountElse}}

                                <div class="no-items-found" >
                                    <i class="icon-magnifier"></i>
                                    <h3></h3>
                                    <p></p>
                                    <a class="btn btn-primary btn-spinner" href="#" role="button"><i class="fa fa-plus"></i>&nbsp; {{ trans('admin.nav-item.actions.create') }}</a>
                                </div>
                            {{$dataCountEndIf}}

                        </div>
                    </div>
                </div>
            </div>
        </div>
   

{{'@'}}endsection