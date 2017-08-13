@extends("la.layouts.app")

@section("contentheader_title", "Configuration")
@section("contentheader_description", "")
@section("section", "Configuration")
@section("sub_section", "")
@section("htmlheader_title", trans('laconfig.configuration'))

@section("headerElems")
@endsection

@section("main-content")

@if (count($errors) > 0)
	<div class="alert alert-danger">
		<ul>
			@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
@endif
<form action="{{route(config('laraadmin.adminRoute').'.la_configs.store')}}" method="POST">
	<!-- general form elements disabled -->
	<div class="box box-warning">
		<div class="box-header with-border">
			<h3 class="box-title">@lang('laconfig.gui_settings')</h3>
		</div>
		<!-- /.box-header -->
		<div class="box-body">
			{{ csrf_field() }}
			<!-- text input -->
			<div class="form-group">
				<label>@lang('laconfig.author')</label>
				<input type="text" class="form-control" placeholder="@lang('laconfig.author')" name="author" value="{{$configs->author}}">
			</div>
			<div class="form-group">
				<label>@lang('laconfig.author_site')</label>
				<input type="text" class="form-control" placeholder="@lang('laconfig.author_site')" name="author_site" value="{{$configs->author_site}}">
			</div>
			<div class="form-group">
				<label>@lang('laconfig.author_email')</label>
				<input type="text" class="form-control" placeholder="@lang('laconfig.author_email')" name="author_email" value="{{$configs->author_email}}">
			</div>
			<div class="form-group">
				<label>@lang('laconfig.sitename')</label>
				<input type="text" class="form-control" placeholder="@lang('laconfig.sitename')" name="sitename" value="{{$configs->sitename}}">
			</div>
			<div class="form-group">
				<label>@lang('laconfig.sitename_first')</label>
				<input type="text" class="form-control" placeholder="@lang('laconfig.sitename_first')" name="sitename_part1" value="{{$configs->sitename_part1}}">
			</div>
			<div class="form-group">
				<label>@lang('laconfig.sitename_second')</label>
				<input type="text" class="form-control" placeholder="@lang('laconfig.sitename_second')" name="sitename_part2" value="{{$configs->sitename_part2}}">
			</div>
			<div class="form-group">
				<label>@lang('laconfig.sitename_short')</label>
				<input type="text" class="form-control" placeholder="@lang('laconfig.sitename_short')" maxlength="2" name="sitename_short" value="{{$configs->sitename_short}}">
			</div>
			<div class="form-group">
				<label>@lang('laconfig.site_description')</label>
				<input type="text" class="form-control" placeholder="@lang('laconfig.site_description_placeholder')" maxlength="140" name="site_description" value="{{$configs->site_description}}">
			</div>
			<!-- checkbox -->
			<div class="form-group">
				<div class="checkbox">
					<label>
						<input type="checkbox" name="sidebar_search" @if($configs->sidebar_search) checked @endif>
						@lang('laconfig.show_searchbar')
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" name="show_messages" @if($configs->show_messages) checked @endif>
						@lang('laconfig.show_message')
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" name="show_notifications" @if($configs->show_notifications) checked @endif>
						@lang('laconfig.show_notification')
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" name="show_tasks" @if($configs->show_tasks) checked @endif>
						@lang('laconfig.show_tasks')
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" name="show_rightsidebar" @if($configs->show_rightsidebar) checked @endif>
						@lang('laconfig.show_right_sidebar')
					</label>
				</div>
			</div>
			<!-- select -->
			<div class="form-group">
				<label>@lang('laconfig.skin_color')</label>
				<select class="form-control" name="skin">
					@foreach($skins as $name=>$property)
						<option value="{{ $property }}" @if($configs->skin == $property) selected @endif>{{ $name }}</option>
					@endforeach
				</select>
			</div>

			<div class="form-group">
				<label>@lang('laconfig.layout')</label>
				<select class="form-control" name="layout">
					@foreach($layouts as $name=>$property)
						<option value="{{ $property }}" @if($configs->layout == $property) selected @endif>{{ $name }}</option>
					@endforeach
				</select>
			</div>

			<div class="form-group">
				<label>@lang('laconfig.default_email')</label>
				<input type="text" class="form-control" placeholder="@lang('laconfig.default_email_placeholder')" maxlength="100" name="default_email" value="{{$configs->default_email}}">
			</div>
		</div><!-- /.box-body -->
		<div class="box-footer">
			<button type="submit" class="btn btn-primary">@lang('global.save')</button>
		</div><!-- /.box-footer -->
	</div><!-- /.box -->
</form>

@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
@endpush

@push('scripts')
<script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>

@endpush
