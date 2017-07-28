@if(!isset($no_padding))
<footer class="main-footer">
    <div class="pull-right hidden-xs">
        Powered by <a href="{{ LAConfigs::getByKey('author_site') }}">{{ LAConfigs::getByKey('author') }}</a>
    </div>
    <strong>Copyright &copy; {{ date('Y') }}
</footer>
@endif
