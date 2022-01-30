<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZekiniAdminPasswordResetsTable extends Migration
{
    public function up()
    {
        $this->down();

        Schema::create('zekini_admin_password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('zekini_admin_password_resets');
    }
}
