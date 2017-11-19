<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddTransactionsAndDictionariesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = "
            CREATE TABLE `dictionary_types` (
              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `name` varchar(255) NOT NULL DEFAULT '',
              `slug` varchar(255) DEFAULT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL,
              PRIMARY KEY (`id`),
              UNIQUE KEY `slug` (`slug`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ";

        DB::statement($sql);


        $sql = "
            CREATE TABLE `dictionaries` (
              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `name` varchar(255) NOT NULL DEFAULT '',
              `value` varchar(255) DEFAULT NULL,
              `dictionary_type_id` int(10) unsigned NOT NULL,
              `sorting` int(10) unsigned NOT NULL DEFAULT '0',
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `dictionary_type_id` (`dictionary_type_id`),
              CONSTRAINT `dictionaries_ibfk_1` FOREIGN KEY (`dictionary_type_id`) REFERENCES `dictionary_types` (`id`) ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ";

        DB::statement($sql);


        $sql = "
            CREATE TABLE `transactions` (
              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `user_id` int(10) unsigned NOT NULL,
              `type_dictionary_id` int(10) unsigned NOT NULL,
              `amount` decimal(10,2) NOT NULL,
              `date` date DEFAULT NULL,
              `description` text,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `user_id` (`user_id`),
              KEY `type_dictionary_id` (`type_dictionary_id`),
              CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
              CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`type_dictionary_id`) REFERENCES `dictionaries` (`id`) ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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
