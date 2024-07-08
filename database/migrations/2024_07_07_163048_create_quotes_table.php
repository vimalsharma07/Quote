<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('quotes', function (Blueprint $table) {
            // Add new columns for text position and alignment
            $table->decimal('text_x', 5, 2)->default(50.00)->after('background_image'); // Text position X in percentage
            $table->decimal('text_y', 5, 2)->default(50.00)->after('text_x'); // Text position Y in percentage
            $table->string('text_align')->default('center')->after('text_y'); // Text alignment (left, center, right)
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            // Drop columns if rolling back
            $table->dropColumn('text_x');
            $table->dropColumn('text_y');
            $table->dropColumn('text_align');
        });
    }
};
