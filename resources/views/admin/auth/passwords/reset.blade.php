@extends('zekini/livewire-crud-generator::admin.layout.master')

@section('title', 'Zekini Admin Reset Password'))

@section('content')

<div class="container">
	<div class="row justify-content-center">
		<div class="col-lg-8">
			<div class="card-group d-block d-md-flex row">
				<div class="card col-md-7 p-4 mb-0">
					<div class="card-body">
						<h1>Reset Password</h1>
						<p class="text-medium-emphasis">Enter Password</p>
						<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/password-reset/reset') }}">
                            @include('zekini/livewire-crud-generator::admin.auth.includes.messages')
                            {{ csrf_field() }}
                            @include('zekini/livewire-crud-generator::admin.auth.includes.messages')
                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="input-group mb-3"><span class="input-group-text">
                                    <svg class="icon">
                                        <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-user"></use>
                                    </svg></span>
                                <input class="form-control" type="email" placeholder="Email" name="email">
                            </div>
                            <div class="input-group mb-4"><span class="input-group-text">
                                    <svg class="icon">
                                        <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-lock-locked"></use>
                                    </svg></span>
                                <input class="form-control" type="password" placeholder="Password" name="password">
                            </div>
                            <div class="input-group mb-4"><span class="input-group-text">
                                    <svg class="icon">
                                        <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-lock-locked"></use>
                                    </svg></span>
                                <input class="form-control" type="password" placeholder="Password Confirmation" name="password_confirmation">
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <button class="btn btn-primary px-4" type="submit">Reset Password</button>
                                </div>
                                
                            </div>
						
					</form>
						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection


@section('bottom-scripts')
<script type="text/javascript">
	// fix chrome password autofill
	// https://github.com/vuejs/vue/issues/1331
	document.getElementById('password').dispatchEvent(new Event('input'));
</script>
@endsection