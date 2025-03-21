<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración para la creación de la tabla 'users'.
 * Esta migración crea la tabla 'users' que contiene los campos 'id', 'email', 'password',
 * 'phone', 'token_to_verify', 'verified', 'code_to_verify' y 'timestamps'.
 *
 * @return void
 *
 * @see Schema Para la creación de la tabla.
 * @see Blueprint Para la creación de los campos de la tabla.
 * @see Migration Para la creación de la migración.
 * @see Schema::create() Para la creación de la tabla.
 * @see Blueprint::id() Para la creación de un campo 'id'.
 * @see Blueprint::string() Para la creación de un campo 'string'.
 * @see Blueprint::unique() Para la creación de un campo 'unique'.
 * @see Blueprint::default() Para la creación de un campo con un valor por defecto.
 * @see Blueprint::boolean() Para la creación de un campo 'boolean'.
 * @see Blueprint::timestamps() Para la creación de los campos 'created_at' y 'updated_at'.
 * @see Blueprint::nullable() Para permitir valores nulos en un campo.
 * @see Schema::dropIfExists() Para eliminar una tabla.
 */

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone')->unique();
            $table->string('token_to_verify')->nullable();
            $table->string('code_to_verify')->nullable();
            $table->boolean('verified')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
