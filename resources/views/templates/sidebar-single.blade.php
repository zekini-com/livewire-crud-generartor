@php
$openBlade = '{{';
$closeBlade = '}}';
@endphp
<li class="nav-item"><a class="nav-link" href="{!! $openBlade !!} {!!$resourceRoute!!} {!! $closeBlade !!}"><i class="nav-icon icon-user"></i> {{ $modelBaseName }}</a></li>