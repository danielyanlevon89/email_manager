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
        Schema::create('email_accounts', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_active')->default(true);

            $table->string('imap_host',60);
            $table->integer('imap_port');
            $table->dateTime('imap_last_execute_time')->nullable();
            $table->integer('imap_last_execute_items_count')->nullable();
            $table->integer('imap_scan_days_count')->default(5);
            $table->enum('imap_encryption',['starttls','ssl','tls']);
            $table->string('imap_username',60);
            $table->string('imap_password',60);
            $table->boolean('imap_validation')->default(false);

            $table->string('smtp_host',60);
            $table->integer('smtp_port');
            $table->integer('smtp_send_email_count_in_minute')->default(5);
            $table->dateTime('smtp_last_execute_time')->nullable();
            $table->integer('smtp_last_execute_items_count')->nullable();
            $table->enum('smtp_encryption',['starttls','ssl','tls']);
            $table->string('smtp_username',60);
            $table->string('smtp_password',60);
            $table->boolean('smtp_validation')->default(false);

            $table->string('email_address',60);
            $table->string('email_from',60);
            $table->longText('auto_reply')->nullable();
            $table->boolean('auto_reply_is_active')->default(false);

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->index([
                'imap_host',
                'imap_port',
                'imap_username',
                'user_id',
                'is_active',
                'imap_last_execute_time',
                'smtp_host',
                'smtp_port',
                'smtp_send_email_count_in_minute',
                'smtp_username',
                'email_address',
                'email_from',
                'auto_reply'
            ]);
        });
    }




    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_accounts');
    }
};
