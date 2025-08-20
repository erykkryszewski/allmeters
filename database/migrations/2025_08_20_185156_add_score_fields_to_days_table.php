<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::table('days', function (Blueprint $table) {
      // score total (simple integer)
      if (!Schema::hasColumn('days', 'score')) {
        $table->integer('score')->default(0);
      }

      // score breakdown json (optional)
      if (!Schema::hasColumn('days', 'score_breakdown')) {
        $table->json('score_breakdown')->nullable();
      }
    });
  }

  public function down(): void
  {
    Schema::table('days', function (Blueprint $table) {
      if (Schema::hasColumn('days', 'score_breakdown')) {
        $table->dropColumn('score_breakdown');
      }
      if (Schema::hasColumn('days', 'score')) {
        $table->dropColumn('score');
      }
    });
  }
};
