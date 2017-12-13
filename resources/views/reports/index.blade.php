@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><b>Отчеты</b></div>
                <div class="panel-heading">
                    <form method="GET" id="js-filters-form">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="date" class="form-control" name="from" value="{{ Request::get('from', date('Y-m-01')) }}">
                            </div>
                            <div class="col-md-4">
                                <input type="date" class="form-control" name="to" value="{{ Request::get('to', date('Y-m-d', strtotime('last day of this month'))) }}">
                            </div>
                            @if (!empty(Auth::user()->family_id))
                                <div class="col-md-4">
                                    <select name="user" class="form-control">
                                        <option value="0">Вся семья</option>
                                        @foreach($familyMembers as $member)
                                            <option value="{{ $member->id }}" {{ (Request::get('user') == $member->id) ? 'selected' : '' }}>
                                                {{ $member->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
                <div class="panel-body">

                    <div class="log-item">
                        <h2>{{_('Траты по дням')}}</h2>
                        <div class="chart" id="transactionsByDays"></div>
                    </div>

                    <div class="log-item">
                        <h2>{{_('Траты по типам')}}</h2>
                        <div class="chart" id="transactionsByTypes"></div>
                    </div>

                    <div class="log-item">
                        <h2>{{_('Траты по пользователям')}}</h2>
                        <div class="chart" id="transactionsByUsers"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('customJS')
    @parent
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="/js/morris.min.js"></script>

    <script>
        $(function () {
            $('#js-filters-form').on('change', 'input, select', function () {
                $('#js-filters-form').submit();
            });
        });

        var transactionsByDays = Object.values({!! $transactionsByDays !!});
        new Morris.Bar({
            element: 'transactionsByDays',
            data: transactionsByDays,
            xkey: 'day',
            ykeys: ['sum'],
            labels: ['Потрачено'],
            horizontal: true
        });

        var transactionsByTypes = Object.values({!! $transactionsByTypes !!});
        new Morris.Bar({
            element: 'transactionsByTypes',
            data: transactionsByTypes,
            xkey: 'type',
            ykeys: ['sum'],
            labels: ['Потрачено'],
            horizontal: true
        });

        var transactionsByUsers = Object.values({!! $transactionsByUsers !!});
        new Morris.Bar({
            element: 'transactionsByUsers',
            data: transactionsByUsers,
            xkey: 'user',
            ykeys: ['sum'],
            labels: ['Потрачено']
        });

    </script>
@endsection
