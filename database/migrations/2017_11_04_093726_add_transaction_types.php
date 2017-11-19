<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTransactionTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = "
            INSERT INTO `dictionary_types` (`id`, `name`, `slug`, `created_at`, `updated_at`)
            VALUES
                (1, 'Типы трансакций', 'transaction_types', '2017-11-04 09:39:37', '2017-11-04 09:39:37');
        ";

        DB::statement($sql);


        $sql = "
            INSERT INTO `dictionaries` (`id`, `name`, `value`, `dictionary_type_id`, `sorting`, `created_at`, `updated_at`)
            VALUES
                (1, 'Разменял валюту', '+', 1, 1, '2017-11-04 09:41:08', '2017-11-04 09:41:08'),
                (2, 'Транспорт (маршрутка)', '-', 1, 0, '2017-11-04 09:41:08', '2017-11-04 09:41:08'),
                (3, 'Транспорт (автомобиль)', '-', 1, 0, '2017-11-04 09:41:08', '2017-11-04 09:41:08'),
                (4, 'Продукты', '-', 1, 0, '2017-11-04 09:41:08', '2017-11-04 09:41:08'),
                (5, 'Медикаменты', '-', 1, 0, '2017-11-04 09:41:08', '2017-11-04 09:41:08'),
                (6, 'Одежда', '-', 1, 0, '2017-11-04 09:41:08', '2017-11-04 09:41:08'),
                (7, 'Хобби', '-', 1, 0, '2017-11-04 09:41:08', '2017-11-04 09:41:08'),
                (8, 'Транспорт (бензин)', '-', 1, 0, '2017-11-04 09:41:08', '2017-11-04 09:41:08');
        ";

        DB::statement($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
