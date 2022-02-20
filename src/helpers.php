<?php

if(! function_exists('admin_roles')) {

    function admin_roles() {
        $roles = collect(config('zekini-admin.admin_roles'))->pluck('name')->toArray();
        return implode(',', $roles);
    }
}