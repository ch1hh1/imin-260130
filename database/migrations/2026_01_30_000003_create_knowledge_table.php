<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('knowledge', function (Blueprint $table) {
            $table->id();
            $table->string('word'); // タイトル
            $table->text('detail'); // 本文
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->date('revised_at')->nullable(); // 改定日
            $table->string('version')->nullable(); // 版
            $table->string('status')->default('draft'); // draft, review, published, archived
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('knowledge');
    }
};
