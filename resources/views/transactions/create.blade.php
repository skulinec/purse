@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <div class="col-md-5 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading">За месяц: <b id="js-amount">{{ $userExpensesByMonth }}</b> грн.</div>
                @if (!empty($lastTransaction))
                    <div class="panel-heading">
                        <spsn class="pull-left">Последняя трансакция:&nbsp;</spsn>
                        <div id="js-last-transaction">
                            <b>{{ $lastTransaction->amount }}</b> грн.
                            <span class="text-muted">{{ $lastTransaction->getType() }}</span>
                            <small>{{ date('d.m.Y', strtotime($lastTransaction->date)) }}</small>
                        </div>
                    </div>
                @endif

                <div class="panel-body">

                    <div id="js-error-block" class="alert alert-danger hide">
                        Произошла ошибка. Пожалуйста, попробуйте еще.
                    </div>

                    <form id="js-add-form" class="form-horizontal" method="POST" action="{{ route('transactions.store') }}">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <div class="col-xs-12">
                                <select id="js-type-select" class="form-control" name="type_dictionary_id" required>
                                    <option value="" data-sign="-">На что</option>
                                    @foreach($transactionTypes as $type)
                                        <option value="{{ $type->id }}" data-sign="{{ $type->value }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-6">
                                <input type="date" class="form-control" name="date" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-xs-6">
                                <input id="js-amount-input" type="number" class="form-control" name="amount" value="" placeholder="Сколько" required autofocus>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12">
                                <input id="js-description-input" type="text" class="form-control" name="description" placeholder="Оправдание">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12">
                                <button id="js-btn-submit" type="submit" class="btn btn-primary">
                                    Потрачено
                                </button>
                            </div>
                        </div>
                    </form>

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
            var body = $('body'),
                btnTexts = {
                    '-': 'Потрачено',
                    '+': 'Пополнено'
                };

            body.on('change', '#js-type-select', function () {
                var sign = $(this).find('option:selected').data()['sign'];
                $('#js-btn-submit').text(btnTexts[sign]);
            });

            body.on('submit', '#js-add-form', function () {
                event.preventDefault();

                $('#overlay').removeClass('hide');
                $('#js-error-block').addClass('hide');

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    dataType: 'json',
                    data: $(this).serialize(),
                    success: function (result) {
                        $('#overlay').addClass('hide');

                        if (result.status) {

                            var replacePlaces = $('#js-amount, #js-last-transaction'),
                                lastTransactionPlace = $('#js-last-transaction');

                            replacePlaces.animate({
                                opacity: 0
                            }, 1000, function() {
                                $('#js-amount').text(result.total);
                                lastTransactionPlace.find('b').text(result.lastAmount);
                                lastTransactionPlace.find('span').text(result.lastType);
                                lastTransactionPlace.find('small').text(result.lastDate);

                                replacePlaces.animate({
                                    opacity: 100
                                }, 500);
                            });

                            $('#js-type-select').prop('selectedIndex', '');
                            $('#js-amount-input').val('');
                            $('#js-description-input').val('');
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