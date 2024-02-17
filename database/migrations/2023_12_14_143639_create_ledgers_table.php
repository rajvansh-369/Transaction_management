<?php

use Carbon\Carbon;
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
        Schema::create('ledgers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->string('bill_no');
            $table->float('total_amount')->nullable();
            $table->float('total_credit')->default(0)->nullable();
            $table->float('total_due')->default(0)->nullable();
            $table->integer('labour')->nullable();
            $table->integer('bardana')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->integer('sms_sent_type')->nullable()->comment("0= days less than 12 1 = days equal to 15 2 = days more than 18");
            $table->timestamp('invoice_date')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ledgers');
    }
};
