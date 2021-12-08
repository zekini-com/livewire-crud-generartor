@php
$openBlade = '{{';
$closeBlade = '}}';
@endphp
<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            <li class="nav-title">Sidebar </li>

           {!! $openBlade !!}{{"--@AutoGenerator--"}}{!! $closeBlade !!} 
            
            <li class="nav-title">{!! $openBlade !!}{!! "trans('brackets/admin-ui::admin.sidebar.settings')" !!}{!! $closeBlade !!} </li>
            <li class="nav-item"><a class="nav-link" href="{!! $openBlade !!} url('admin/admin-users') {!! $closeBlade !!}"><i class="nav-icon icon-user"></i> {{ __('Manage access') }}</a></li>
        </ul>
    </nav>
    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>
