@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Мои расходы</div>
                <div class="panel-body">

                    <div id="js-error-block" class="alert alert-danger hide">
                        Произошла ошибка. Пожалуйста, попробуйте еще.
                    </div>

                    <table class="table table-sm table-striped hidden-xs">
                        <thead>
                            <tr>
                                <th>Дата</th>
                                <th>Сумма</th>
                                <th>Тип</th>
                                <th>Описание</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $transaction)
                                <tr class="js-transaction-item">
                                    <td>{{ date('d.m.Y', strtotime($transaction->date)) }}</td>
                                    <th>{{ $transaction->amount }}</th>
                                    <td>{{ $transaction->getType() }}</td>
                                    <td>{{ $transaction->description }}</td>
                                    <td>
                                        <a href="{{ route('transactions.destroy', $transaction->id) }}" class="js-transaction-delete-button" style="font-size: 11px;">
                                            Удалить
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <ul class="list-group visible-xs">
                        @foreach($transactions as $transaction)
                            <li class="js-transaction-item list-group-item list-group-item-action flex-column align-items-start">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">
                                        {{ date('d.m.Y', strtotime($transaction->date)) }}
                                        <span class="badge">
                                            {{ $transaction->amount }}
                                        </span>
                                    </h5>
                                    <small>{{ $transaction->getType() }}</small>
                                </div>
                                <p class="mb-1">{{ $transaction->description }}</p>
                                <small>
                                    <a href="{{ route('transactions.destroy', $transaction->id) }}" class="js-transaction-delete-button">
                                        Удалить
                                    </a>
                                </small>
                            </li>
                        @endforeach
                    </ul>
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('customJS')
    @parent
    <script>
        $(function () {
            $('.js-transaction-delete-button').on('click', function () {
                event.preventDefault();

                if (confirm('Точно удалить?') != true) {
                    return false;
                }

                var item = $(this).closest('.js-transaction-item');

                $('#overlay').removeClass('hide');
                $('#js-error-block').addClass('hide');

                $.ajax({
                    url: $(this).attr('href'),
                    type: 'DELETE',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (result) {
                        $('#overlay').addClass('hide');

                        if (result.status) {
                            item.animate({
                                opacity: 0
                            }, 1000, function() {
                                item.remove();
                            });
                        } else {
                            $('#js-error-block').removeClass('hide');
                        }
                    },
                    error: function () {
                        $('#overlay').addClass('hide');
                        $('#js-error-block').removeClass('hide');
                    }
                });

                return false;
            });
        })
    </script>
@endsection
