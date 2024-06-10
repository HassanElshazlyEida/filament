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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name');
            $table->string('address');
            $table->char('zip_code');
            $table->date("date_of_birth");
            $table->date("date_hired");
            $table->foreignIdFor(\App\Models\Department::class)->nullable()->constrained()->onDelete('set null');
            $table->foreignIdFor(\App\Models\City::class)->nullable()->constrained()->onDelete('set null');
            $table->foreignIdFor(\App\Models\Country::class)->nullable()->constrained()->onDelete('set null');
            $table->foreignIdFor(\App\Models\State::class)->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
