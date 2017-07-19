@lang('emails.dear_name', ['name' => $user->name]),<br><br>

@lang('emails.new_account.registered_on', ['url' => url('/')]).<br><br>

@lang('emails.new_account.credentials'):<br><br>

@lang('emails.name'): {{ $user->email }}<br>
@lang('emails.password'): {{ $password }}<br><br>

@lang('emails.access_link', ['url' => url('/login'), 'link' => str_replace("http://", "", url('/login'))]).<br><br>

@lang('emails.regards'),
