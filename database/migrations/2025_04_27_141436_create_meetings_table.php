<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // عنوان الاجتماع
            $table->foreignId('client_id')->constrained()->onDelete('cascade'); // العميل المرتبط بالاجتماع

            $table->text('notes')->nullable(); // ملاحظات الاجتماع
            $table->timestamp('scheduled_at'); // موعد الاجتماع
            $table->enum('status', ['scheduled', 'completed', 'cancelled'])->default('scheduled'); // حالة الاجتماع
            $table->timestamps(); // الحقول المدمجة: created_at و updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meetings');
    }
};
