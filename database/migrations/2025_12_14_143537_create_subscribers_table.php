<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('sim');
            $table->string('zip');
            $table->string('plan_soc');
            $table->string('imei');
            $table->string('label');
            $table->string('e911_address_street1');
            $table->string('e911_address_street2')->nullable();
            $table->string('e911_address_city');
            $table->string('e911_address_state');
            $table->string('e911_address_zip');
            $table->string('transaction_id')->nullable();
            $table->string('msisdn')->nullable();
            $table->string('iccid')->nullable();
            $table->string('account_id')->nullable();
            $table->string('api_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscribers');
    }
};
