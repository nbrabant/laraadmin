@extends("la.layouts.app")

<?php
use Dwij\Laraadmin\Models\Module;
?>

@section("contentheader_title", trans('global.modules'))
@section("contentheader_description", trans('global.listing_type', ['type' => trans('global.module')]))
@section("section", trans('global.modules'))
@section("sub_section", trans('global.listing'))
@section("htmlheader_title", trans('global.listing_type', ['type' => trans('global.module')]))

@section("headerElems")
<button class="btn btn-success btn-sm pull-right " data-toggle="modal" data-target="#AddExistModal">@lang('global.from_existing')</button>
<button class="btn btn-success btn-sm pull-right " style="margin-right:5px;" data-toggle="modal" data-target="#AddModal">@lang('global.add_type', ['type' => trans('global.module')])</button>
@endsection

@section("main-content")

<div class="box box-success">
	<!--<div class="box-header"></div>-->
	<div class="box-body">
		<table id="dt_modules" class="table table-bordered">
		<thead>
		<tr class="success">
			<th>@lang('global.id')</th>
			<th>@lang('global.name')</th>
			<th>@lang('global.table')</th>
			<th>@lang('global.items')</th>
			<th>@lang('global.actions')</th>
		</tr>
		</thead>
		<tbody>

			@foreach ($modules as $module)
				<tr>
					<td>{{ $module->id }}</td>
					<td><a href="{{ url(config('laraadmin.adminRoute') . '/modules/'.$module->id) }}">{{ $module->label }}</a></td>
					<td>{{ $module->name_db }}</td>
					<td>{{ Module::itemCount($module->name) }}</td>
					<td>
						<a module_label="{{ $module->label }}" module_icon="{{ $module->fa_icon }}" module_id="{{ $module->id }}" class="btn btn-primary btn-xs update_module" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>
						<a href="{{ url(config('laraadmin.adminRoute') . '/modules/'.$module->id)}}#access" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-key"></i></a>
						<a href="{{ url(config('laraadmin.adminRoute') . '/modules/'.$module->id)}}#sort" class="btn btn-success btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-sort"></i></a>
						<a module_name="{{ $module->name }}" module_id="{{ $module->id }}" class="btn btn-danger btn-xs delete_module" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-trash"></i></a>
					</td>
				</tr>
			@endforeach
		</tbody>
		</table>
	</div>
</div>

<div class="modal fade" id="AddExistModal" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="@lang('global.close')"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">@lang('global.from_existing')</h4>
			</div>
			{!! Form::open(['route' => config('laraadmin.adminRoute') . '.modules.store', 'id' => 'module-add-form']) !!}
			<div class="modal-body">
				<div class="box-body">
					<!--<div class="form-group">
						<label for="name">Module Name :</label>
						{{ Form::text("name", null, ['class'=>'form-control', 'placeholder'=>'Module Name', 'data-rule-minlength' => 2, 'data-rule-maxlength'=>20, 'required' => 'required']) }}
					</div>-->
					<div class="form-group">
						<label for="table">Table</label>
						<?php
						$default_val ='';
						$table_module = array();
						foreach($tables as $table) {
							$modItems = Module::where('name_db', $table)->first();
							if(!isset($modItems)) {
								$table_module[$table] = $table;
							}
						}
						?>
						{{ Form::select("name", $table_module, $default_val, ['class'=>'form-control', 'rel' => '']) }}
					</div>
					<div class="form-group">
						<label for="icon">@lang('global.icon')</label>
						<div class="input-group">
							<input class="form-control" placeholder="FontAwesome Icon" name="icon" type="text" value="fa-cube"  data-rule-minlength="1" required>
							<span class="input-group-addon"></span>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">@lang('global.close')</button>
				{!! Form::submit( trans('global.submit'), ['class'=>'btn btn-success']) !!}
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>

<div class="modal fade" id="AddModal" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="@lang('global.close')"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">@lang('global.add_type', ['type' => trans('global.module')])</h4>
			</div>
			{!! Form::open(['route' => config('laraadmin.adminRoute') . '.modules.store', 'id' => 'module-add-form']) !!}
			<div class="modal-body">
				<div class="box-body">
					<div class="form-group">
						<label for="name">Module Name :</label>
						{{ Form::text("name", null, ['class'=>'form-control', 'placeholder'=>'Module Name', 'data-rule-minlength' => 2, 'data-rule-maxlength'=>20, 'required' => 'required']) }}
					</div>
					<div class="form-group">
						<label for="icon">@lang('global.icon')</label>
						<div class="input-group">
							<input class="form-control" placeholder="FontAwesome Icon" name="icon" type="text" value="fa-cube"  data-rule-minlength="1" required>
							<span class="input-group-addon"></span>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">@lang('global.close')</button>
				{!! Form::submit( trans('global.submit'), ['class'=>'btn btn-success']) !!}
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>

<!-- module deletion confirmation  -->
<div class="modal" id="module_delete_confirm">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="@lang('global.close')">
					<span aria-hidden="true">×</span>
				</button>
				<h4 class="modal-title">@lang('global.delete_type', ['type' => trans('global.module')])</h4>
			</div>
			<div class="modal-body">
				<p>Do you really want to delete module <b id="moduleNameStr" class="text-danger"></b> ?</p>
				<p>Following files will be deleted:</p>
				<div id="moduleDeleteFiles"></div>
				<p class="text-danger">Note: Migration file will not be deleted but modified.</p>
			</div>
			<div class="modal-footer">
				{{ Form::open(['route' => [config('laraadmin.adminRoute') . '.modules.destroy', 0], 'id' => 'module_del_form', 'method' => 'delete', 'style'=>'display:inline']) }}
					<button class="btn btn-danger btn-delete pull-left" type="submit">Yes</button>
				{{ Form::close() }}
				<a data-dismiss="modal" class="btn btn-default pull-right" >No</a>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>

<!-- module update confirmation  -->
<div class="modal" id="module_update">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="@lang('global.close')"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">@lang('global.update_type', ['type' => trans('global.module')])</h4>
			</div>
			<form id="module-update-form" role="form" action="{{ url('module_edit_submit') }}" class="smart-form" novalidate="novalidate" method="post">
                {{ csrf_field() }}
				<div class="modal-body">
					<div class="box-body">
						<div class="form-group">
							<label for="name">Module Name :</label>
							<input type="text"  class="form-control module_label_edit" placeholder="Module Name" name="Module Name" value=""/>
						</div>
						<div class="form-group">
							<label for="icon">@lang('global.icon')</label>
							<div class="input-group">
								<input type="text" class="form-control module_icon_edit"  placeholder="FontAwesome Icon" name="icon"  value=""  data-rule-minlength="1" required>
								<span class="input-group-addon update-icon"></span>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">@lang('global.close')</button>
					<button type="button" class="btn btn-success save_edit_module" data-dismiss="modal">@lang('global.save')</button>
				</div>
			</form>
		</div>
	</div>
	<!-- /.modal-dialog -->
</div>
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
@endpush

@push('scripts')
<script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('la-assets/plugins/iconpicker/fontawesome-iconpicker.js') }}"></script>
<script>
$(function () {
	$('.delete_module').on("click", function () {
    	var module_id = $(this).attr('module_id');
		var module_name = $(this).attr('module_name');
		$("#moduleNameStr").html(module_name);
		$url = $("#module_del_form").attr("action");
		$("#module_del_form").attr("action", $url.replace("/0", "/"+module_id));
		$("#module_delete_confirm").modal('show');
		$.ajax({
			url: "{{ url(config('laraadmin.adminRoute') . '/get_module_files/') }}/" + module_id,
			type:"POST",
			beforeSend: function() {
				$("#moduleDeleteFiles").html('<center><i class="fa fa-refresh fa-spin"></i></center>');
			},
			headers: {
		    	'X-CSRF-Token': '{{ csrf_token() }}'
    		},
			success: function(data) {
				var files = data.files;
				var filesList = "<ul>";
				for ($i = 0; $i < files.length; $i++) {
					filesList += "<li>" + files[$i] + "</li>";
				}
				filesList += "</ul>";
				$("#moduleDeleteFiles").html(filesList);
			}
		});
	});

	$('.update_module').on("click", function () {
    	var module_id = $(this).attr('module_id');
		var module_label = $(this).attr('module_label');
		var module_icon = $(this).attr('module_icon');
		$(".module_label_edit").val(module_label);
		$(".module_icon_edit").val(module_icon);
		$("#module_update").modal('show');
		$(".update-icon").html('<center><i class="fa '+module_icon+'"></i></center>');

		$('.save_edit_module').on("click", function () {
			var module_label = $(".module_label_edit").val();
			var module_icon = $(".module_icon_edit").val();
			$.ajax({
				url: "{{ url(config('laraadmin.adminRoute') . '/module_update') }}",
				type:"POST",
				data : {'id':module_id,'label':module_label, 'icon':module_icon, '_token': '{{ csrf_token() }}' },
				success: function(data) {
					location.reload();
				}
			});
		});
	});

	$('input[name=icon]').iconpicker();
	$("#dt_modules").DataTable({

	});
	$("#module-add-form").validate({

	});
});
</script>
@endpush
