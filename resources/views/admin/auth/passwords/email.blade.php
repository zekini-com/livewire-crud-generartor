@extends('zekini/livewire-crud-generator::admin.layout.master')

@section('title', 'Zekini Admin Login'))

@section('content')

<div class="container">
	<div class="row justify-content-center">
		<div class="col-lg-8">
			<div class="card-group d-block d-md-flex row">
				<div class="card col-md-7 p-4 mb-0">
					<div class="card-body">
						<h1>Reset Password</h1>
						<p class="text-medium-emphasis">Enter Email</p>
						<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/password-reset/send') }}">
						@include('zekini/livewire-crud-generator::admin.auth.includes.messages')
						{{ csrf_field() }}
						<div class="input-group mb-3"><span class="input-group-text">
								<svg class="icon">
									<use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-user"></use>
								</svg></span>
							<input class="form-control" type="email" placeholder="Email" name="email">
						</div>
			
						<div class="row">
							<div class="col-6">
								<button class="btn btn-primary px-4" type="submit">Send Reset Email</button>
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