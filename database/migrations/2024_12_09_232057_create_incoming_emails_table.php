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
        Schema::create('incoming_emails', function (Blueprint $table) {
            $table->id();
            $table->string('from');
            $table->string('to');
            $table->string('cc')->nullable();
            $table->string('subject');
            $table->longText('body');
            $table->dateTime('email_date');
            $table->dateTime('seen_at')->nullable();
            $table->string('message_id')->nullable();
            $table->string('reply_message_id')->nullable();
            $table->boolean('has_attachment')->default(false);
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('email_accounts_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->index([
                'from',
                'to',
                'cc',
                'user_id',
                'subject',
                'email_accounts_id',
                'reply_message_id',
                'email_accounts_id',
                'email_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incoming_emails');
    }
};
