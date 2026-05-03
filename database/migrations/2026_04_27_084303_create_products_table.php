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
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        // Связь с таблицей categories
        $table->foreignId('category_id')->constrained()->cascadeOnDelete();
        
        $table->string('name');
        $table->string('slug')->unique();
        $table->text('description')->nullable(); 
        
        // ВОТ ОНО! Добавляем фото сразу при создании таблицы
        $table->string('image')->nullable(); 
        
        $table->decimal('price', 10, 2); 
        $table->integer('quantity')->default(0); 
        $table->boolean('is_active')->default(true); 
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
