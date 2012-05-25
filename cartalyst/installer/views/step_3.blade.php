@layout('installer::template')

@section('content')

<div id="installer" class="row-fluid">
  <div class="span12 well">
    <h1>Step 3</h1>

{{ Form::open(null, 'POST', array('class' => 'form-inline')) }}

	<fieldset>
		<legend>Create Admin User</legend>

		<div class="control-group">
			{{ Form::label('first_name', 'First Name', array('class' => 'control-label', 'for' => 'inputIcon')) }}
         	<div class="controls">
            	<div class="input-prepend">
            		<span class="add-on"><i class="icon-user"></i></span>{{ Form::text('first_name', null, array('placeholder' => 'e.g. John')) }}
            	</div>
        	</div>
        </div>

        <div class="control-group">
			{{ Form::label('last_name', 'Last Name', array('class' => 'control-label', 'for' => 'inputIcon')) }}
         	<div class="controls">
            	<div class="input-prepend">
            		<span class="add-on"><i class="icon-user"></i></span>{{ Form::text('last_name', null, array('placeholder' => 'e.g. Doe')) }}
            	</div>
        	</div>
        </div>

        <div class="control-group">
			{{ Form::label('email', 'Email', array('class' => 'control-label', 'for' => 'inputIcon')) }}
         	<div class="controls">
            	<div class="input-prepend">
            		<span class="add-on"><i class="icon-user"></i></span>{{ Form::text('email', null, array('placeholder' => 'e.g. email@example.com')) }}
            	</div>
        	</div>
        </div>

        <div class="control-group">
			{{ Form::label('password', 'Password', array('class' => 'control-label', 'for' => 'inputIcon')) }}
         	<div class="controls">
            	<div class="input-prepend">
            		<span class="add-on"><i class="icon-lock"></i></span>{{ Form::password('password') }}
            	</div>
        	</div>
        </div>

        <div class="control-group">
			{{ Form::label('password_confirmation', 'Confirm Password', array('class' => 'control-label', 'for' => 'inputIcon')) }}
         	<div class="controls">
            	<div class="input-prepend">
            		<span class="add-on"><i class="icon-lock"></i></span>{{ Form::password('password_confirmation') }}
            	</div>
        	</div>
        </div>


		<div class="control-group pager">
			<div class="controls">
				<button type="submit" disabled="disabled" class="btn btn-primary">
					Finish
				</button>
			</div>
		</div>
	</fieldset>

{{ Form::close() }}

  </div>
</div>

@endsection
