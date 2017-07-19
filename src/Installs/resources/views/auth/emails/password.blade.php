{{-- resources/views/emails/password.blade.php --}}

@lang('emails.change_password.reset_link'): <a href="{{ url('password/reset/'.$token) }}">{{ url('password/reset/'.$token) }}</a>
