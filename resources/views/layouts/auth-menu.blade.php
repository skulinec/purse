<a href="{{ route('/') }}">Добавить расходы</a>
<a href="{{ route('transactions') }}">Мои платежи</a>
{{--<a href="{{ route('reports') }}">Отчеты</a>--}}
<a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
    Выйти
</a>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    {{ csrf_field() }}
</form>
