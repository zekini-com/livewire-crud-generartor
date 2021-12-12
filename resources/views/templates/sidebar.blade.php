@php
$openBlade = '{{';
$closeBlade = '}}';
@endphp
<div class="sidebar sidebar-dark sidebar-fixed" id="sidebar">
    <div class="sidebar-brand d-none d-md-flex">
        <svg class="sidebar-brand-full" width="118" height="46" alt="CoreUI Logo">
            <use xlink:href="assets/brand/coreui.svg#full"></use>
        </svg>
        <svg class="sidebar-brand-narrow" width="46" height="46" alt="CoreUI Logo">
            <use xlink:href="assets/brand/coreui.svg#signet"></use>
        </svg>
    </div>
    <ul class="sidebar-nav" data-coreui="navigation" data-simplebar="">
        <li class="nav-item"><a class="nav-link" href="{!! $openBlade !!} url('/admin') {!! $closeBlade !!}">
                <svg class="nav-icon">
                    <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-speedometer"></use>
                </svg> Dashboard<span class="badge badge-sm bg-info ms-auto">NEW</span></a></li>
        
        <li class="nav-title">CRUD</li>
    
        {!! $openBlade !!}{{"--@AutoGenerator--"}}{!! $closeBlade !!} 

        <li class="nav-item"><a class="nav-link" href="{!! $openBlade !!} url('/admin/audit/logs') {!! $closeBlade !!}">
                <svg class="nav-icon">
                    <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-speedometer"></use>
                </svg> Audit Logs</a></li>

        <li class="nav-item"><a class="nav-link" href="{!! $openBlade !!} url('/admin/logout') {!! $closeBlade !!}">
                <svg class="nav-icon">
                    <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-speedometer"></use>
                </svg> Logout</a></li>
        
    
    </ul>
    <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
</div>

