<?php

use App\Models\ApiKey;
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
        Schema::create('api_keys', static function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('uuid', ApiKey::UUID['length'])->comment('暗号化されたUUID（APIキーの主要部分）');
            $table->char('suffix', ApiKey::SUFFIX['length'])->collation('utf8mb4_bin')->index()->comment('APIキーの接尾語');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_keys');
    }
};
