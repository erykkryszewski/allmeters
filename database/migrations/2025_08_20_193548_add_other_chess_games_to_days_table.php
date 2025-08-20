<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::table('days', function (Blueprint $table) {
      // boolean flag: were there any other games (blitz/bullet/extra classical)?
      $table->boolean('other_chess_games')->default(false)->after('chess_games');
    });
  }

  public function down(): void
  {
    Schema::table('days', function (Blueprint $table) {
      $table->dropColumn('other_chess_games');
    });
  }
};
