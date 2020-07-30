<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationMessageSendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_message_sends', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("notification_message_id")->unsigned();
            $table->bigInteger("main_distributor_id")->unsigned()->nullable();
            $table->bigInteger("sub_distributor_id")->unsigned()->nullable();
            $table->enum("status", ["Sent","Accepted", "Rejected"]);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("notification_message_id")->on("notification_messages")->references("id")->onUpdate("cascade")->onDelete("cascade");
            $table->foreign("main_distributor_id")->on("main_distributors")->references("id")->onUpdate("cascade")->onDelete("cascade");
            $table->foreign("sub_distributor_id")->on("sub_distributors")->references("id")->onUpdate("cascade")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notification_message_sends');
    }
}
