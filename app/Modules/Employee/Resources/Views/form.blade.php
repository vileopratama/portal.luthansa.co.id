@extends('administrator::layout',['title' => $page_title])
@section('content')
	{!! Form::open(['url' => 'employee/do-update','id'=>'user_form','class'=>'form-horizontal']) !!}
	{!! Form::hidden('id', isset($employee) ?  Crypt::encrypt($employee->id) : null, ['id' => 'id']) !!}
    
	<!-- Panel Header -->
	<div class="row">
        <div class="col-md-3">
			
		</div>
		<div class="col-md-9">
            <div class="mail-toolbar m-b-lg pull-right">
                <div class="pull-right">
                    <div class="btn-group pull-right">
						@if(App::access('u','employee'))
						<button class="btn btn-primary btn-md" type="submit" id="btn-submit"><i class="fa fa-save"></i> {!! Lang::get('global.save') !!}</button>
						@endif
						<a href="{!! url('employee/') !!}" class="btn btn-primary btn-md"><i class="fa fa-undo"></i> {!! Lang::get("global.back") !!}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Panel Header -->
    
	<div class="row">
        <div class="col-md-12">
            <div class="widget p-lg">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="name" class="col-sm-3 control-label text-left">{!! Lang::get('global.name') !!}</label>
                            <div class="col-sm-9">
								{!! Form::text('name',isset($employee)?$employee->name:null, ['class' => 'form-control input-md','id'=>'name','placeholder'=>lang::get('global.name'),'maxlength'=>100]) !!}
							</div>
                        </div>
						
						<div class="form-group">
							<label for="name" class="col-sm-3 control-label text-left">{!! Lang::get('global.gender') !!}</label>
                            <div class="col-sm-9">
								<label class="checkbox-inline">{!! Form::radio('gender', 'Male',(isset($employee) && $employee->gender=='Male' ? true :false),['id' => 'gender']) !!} {!! Lang::get('global.male') !!}</label>
								<label class="checkbox-inline">{!! Form::radio('gender', 'Female',(isset($employee) && $employee->gender=='Female' ? true :false),['id' => 'gender']) !!} {!! Lang::get('global.female') !!}</label>
							</div>
                        </div>
						
						<div class="form-group">
							<label for="email" class="col-sm-3 control-label text-left">{!! Lang::get('global.birth date') !!}</label>
                            <div class="col-sm-4">
								<div class="input-group date" id="birth_date">
									<input name="birth_date" type="text" class="form-control" value="{!! isset($employee) ? $employee->birth_date : null !!}"  />
									<span class="input-group-addon bg-info text-white">
										<span class="glyphicon glyphicon-calendar"></span>
									</span>
								</div>
							</div>
                        </div>
						
						<div class="form-group">
							<label for="name" class="col-sm-3 control-label text-left">{!! Lang::get('global.birth place') !!}</label>
                            <div class="col-sm-9">
								{!! Form::text('birth_place',isset($employee)?$employee->birth_place:null, ['class' => 'form-control input-md','id'=>'birth_place','placeholder'=>lang::get('global.birth place'),'maxlength'=>100]) !!}
							</div>
                        </div>
						
						<div class="form-group">
							<label for="name" class="col-sm-3 control-label text-left">{!! Lang::get('global.identity number') !!}</label>
                            <div class="col-sm-9">
								{!! Form::text('identity_number',isset($employee)?$employee->identity_number:null, ['class' => 'form-control input-md','id'=>'identity_number','placeholder'=>lang::get('global.identity number'),'maxlength'=>100]) !!}
							</div>
                        </div>
						
						<div class="form-group">
							<label for="email" class="col-sm-3 control-label text-left">{!! Lang::get('global.identity validity period') !!}</label>
                            <div class="col-sm-4">
								<div class="input-group date" id="identity_validity_period" >
									<input name="identity_validity_period" type="text" class="form-control" value="{!! isset($employee) ? $employee->identity_validity_period : null !!}" />
									<span class="input-group-addon bg-info text-white">
										<span class="glyphicon glyphicon-calendar"></span>
									</span>
								</div>
							</div>
                        </div>
						
						<div class="form-group">
							<label for="address" class="col-sm-3 control-label text-left">{!! Lang::get('global.address') !!}</label>
                            <div class="col-sm-9">
								{!! Form::textarea('address',isset($employee)?$employee->address:null, ['rows'=>3,'class' => 'form-control input-md','id'=>'address','placeholder'=>lang::get('global.address'),'maxlength'=>255]) !!}
							</div>
                        </div>
						
						<div class="form-group">
							<label for="city" class="col-sm-3 control-label text-left">{!! Lang::get('global.city') !!}</label>
                            <div class="col-sm-9">
								{!! Form::text('city',isset($employee)?$employee->city:null, ['class' => 'form-control input-md','id'=>'city','placeholder'=>lang::get('global.city'),'maxlength'=>100]) !!}
							</div>
                        </div>
						
						<div class="form-group">
							<label for="zip_code" class="col-sm-3 control-label text-left">{!! Lang::get('global.zip code') !!}</label>
                            <div class="col-sm-3">
								{!! Form::text('zip_code',isset($employee)?$employee->zip_code:null, ['class' => 'form-control input-md','id'=>'zip_code','placeholder'=>lang::get('global.zip code'),'maxlength'=>5]) !!}
							</div>
                        </div>
						
					</div>
					
					<div class="col-md-6">
						<div class="form-group">
							<label for="name" class="col-sm-3 control-label text-left">{!! Lang::get('global.nip') !!}</label>
                            <div class="col-sm-3">
								{!! Form::text('nip_01',isset($employee)?substr($employee->nip,0,8):null, ['class' => 'form-control input-md','id'=>'nip','placeholder'=>lang::get('global.nip'),'maxlength'=>9]) !!}
							</div>
							<div class="col-sm-3">
								{!! Form::text('nip_02',isset($employee)?substr($employee->nip,9,5):null, ['class' => 'form-control input-md','id'=>'nip_02','placeholder'=>lang::get('global.nip'),'maxlength'=>5]) !!}
							</div>
                        </div>
						<div class="form-group">
							<label for="name" class="col-sm-3 control-label text-left">{!! Lang::get('global.sim number') !!}</label>
                            <div class="col-sm-9">
								{!! Form::text('sim_number',isset($employee)?$employee->sim_number:null, ['class' => 'form-control input-md','id'=>'sim_number','placeholder'=>lang::get('global.sim number'),'maxlength'=>100]) !!}
							</div>
                        </div>
						
						<div class="form-group">
							<label for="email" class="col-sm-3 control-label text-left">{!! Lang::get('global.sim validity period') !!}</label>
                            <div class="col-sm-4">
								<div class="input-group date" id="sim_validity_period" >
									<input name="sim_validity_period" type="text" class="form-control" value="{!! isset($employee) ? $employee->sim_validity_period : null !!}" />
									<span class="input-group-addon bg-info text-white">
										<span class="glyphicon glyphicon-calendar"></span>
									</span>
								</div>
							</div>
                        </div>
						
						<div class="form-group">
							<label for="department_id" class="col-sm-3 control-label text-left">{!! Lang::get('global.department') !!}</label>
                            <div class="col-sm-9">
								{!! Form::select('department_id',\App\Modules\Department\Department::list_dropdown(),isset($employee)?$employee->department_id:null, ['class' => 'form-control input-md','id'=>'department_id','maxlength'=>11]) !!}
							</div>
                        </div>
						
						<div class="form-group">
							<label for="city" class="col-sm-3 control-label text-left">{!! Lang::get('global.position') !!}</label>
                            <div class="col-sm-9">
								{!! Form::text('position',isset($employee)?$employee->position:null, ['class' => 'form-control input-md','id'=>'position','placeholder'=>lang::get('global.position'),'maxlength'=>100]) !!}
							</div>
                        </div>
						
						
						<div class="form-group">
							<label for="city" class="col-sm-3 control-label text-left">{!! Lang::get('global.bank account no') !!}</label>
                            <div class="col-sm-9">
								{!! Form::text('bank_account_no',isset($employee)?$employee->bank_account_no:null, ['class' => 'form-control input-md','id'=>'bank_account_no','placeholder'=>lang::get('global.bank account no'),'maxlength'=>100]) !!}
							</div>
                        </div>
						
						<div class="form-group">
							<label for="city" class="col-sm-3 control-label text-left">{!! Lang::get('global.bank account name') !!}</label>
                            <div class="col-sm-9">
								{!! Form::text('bank_account_name',isset($employee)?$employee->bank_account_name:null, ['class' => 'form-control input-md','id'=>'bank_account_name','placeholder'=>lang::get('global.bank account name'),'maxlength'=>100]) !!}
							</div>
                        </div>
						
						<div class="form-group">
							<label for="email" class="col-sm-3 control-label text-left">{!! Lang::get('global.email') !!}</label>
                            <div class="col-sm-9">
								{!! Form::text('email',isset($employee)?$employee->email:null, ['class' => 'form-control input-md','id'=>'email','placeholder'=>lang::get('global.email'),'maxlength'=>100]) !!}
							</div>
                        </div>
						
						<div class="form-group">
							<label for="phone_number" class="col-sm-3 control-label text-left">{!! Lang::get('global.phone number') !!}</label>
                            <div class="col-sm-9">
								{!! Form::text('phone_number',isset($employee)?$employee->phone_number:null, ['class' => 'form-control input-md','id'=>'phone_number','placeholder'=>lang::get('global.phone number'),'maxlength'=>18]) !!}
							</div>
                        </div>
						
						
					</div>
					
					
					
				</div>
            </div>
        </div>
    </div>
	{!! Form::close() !!}
@endsection

@push('css')
	<link href="{!! asset('vendor/bootstrap-select2/css/select2.min.css') !!}" rel="stylesheet"/>	
@endpush

@push('scripts')
	<script src="{!! asset('vendor/bootstrap-select2/js/select2.min.js') !!}"></script>
    <script type="text/javascript">
		$(function() {
			$('#birth_date').datetimepicker({
				format: 'DD/MM/YYYY',
				allowInputToggle: true,
				useCurrent: true
			});
			$('#identity_validity_period').datetimepicker({
				format: 'DD/MM/YYYY',
				allowInputToggle: true,
				useCurrent: true
			});
			$('#sim_validity_period').datetimepicker({
				format: 'DD/MM/YYYY',
				allowInputToggle: true,
				useCurrent: true
			});
			$("#company_id").select2();	
			$("#employee_category_id").select2();	
			$('#user_form').on('submit', function(event) {
				event.preventDefault();
				$("div#divLoading").addClass('show');
				$.ajax({
					type : $(this).attr('method'),
					url : $(this).attr('action'),
					data : $(this).serialize(),
					dataType : "json",
					cache : false,
					beforeSend : function() { console.log($(this).serialize());},
					success : function(response) {
						$(".help-block").remove();
						if(response.success == false) {
							$.each(response.message, function( index,message) {
								var element = $('<p>' + message + '</p>').attr({'class' : 'help-block text-danger'}).css({display: 'none'});
								$('#'+index).after(element);
								$(element).fadeIn();
							});
						}
						else {
							$.alert(response.message);
							$(".help-block").remove();
							window.location = response.redirect;
						}
						
						$("div#divLoading").removeClass('show');
					},
					error : function() {
						$(".help-block").remove();
						$("div#divLoading").removeClass('show');
					}
				});
				return false;
			});
		});
	</script>
@endpush
