@php
$openBlade = '{{';
$closeBlade = '}}';
@endphp
<li class="nav-item"><a class="nav-link" href="{!! $openBlade !!} {!!$resourceRoute!!} {!! $closeBlade !!}">
                <svg class="nav-icon">
                    <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-chart-pie"></use>
                </svg> {{ $modelBaseName }}</a>
        </li>