<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        DB::statement("ALTER TABLE user_payout_methods MODIFY payout_method ENUM('paypal','payoneer','bank','QR') NOT NULL;");
    }

    public function down()
    {
        DB::statement("ALTER TABLE user_payout_methods MODIFY payout_method ENUM('paypal','payoneer','bank') NOT NULL;");
    }
};
