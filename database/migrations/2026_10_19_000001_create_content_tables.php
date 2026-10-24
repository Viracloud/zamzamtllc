<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Page-builder content: pages made of ordered sections, sections holding
     * repeatable items, and the services catalogue.
     */
    public function up(): void
    {
        Schema::create('site_pages', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();          // stable id used in code (home, quote, careers…) or a generated one
            $table->string('name');                   // admin label
            $table->string('slug')->nullable()->unique(); // URL path; null = home page
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->boolean('is_system')->default(false); // system pages can't be deleted
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('page');                   // site_pages.key, or "global" for header/footer/forms
            $table->string('type');
            $table->string('name')->nullable();       // optional admin label
            $table->string('kicker')->nullable();
            $table->text('title')->nullable();
            $table->text('subtitle')->nullable();
            $table->longText('body')->nullable();
            $table->string('button_text')->nullable();
            $table->string('button_link', 500)->nullable();
            $table->string('button2_text')->nullable();
            $table->string('button2_link', 500)->nullable();
            $table->string('image')->nullable();
            $table->json('extra')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->index(['page', 'sort_order']);
        });

        Schema::create('section_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained()->cascadeOnDelete();
            $table->string('group')->default('items');
            $table->string('icon')->nullable();
            $table->text('title')->nullable();
            $table->text('subtitle')->nullable();
            $table->text('body')->nullable();
            $table->string('value')->nullable();
            $table->string('link', 500)->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->index(['section_id', 'group', 'sort_order']);
        });

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('short')->nullable();
            $table->string('icon')->nullable();
            $table->string('image')->nullable();
            $table->string('image_alt')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('intro')->nullable();        // paragraphs separated by a blank line
            $table->text('features')->nullable();     // one per line
            $table->text('ideal')->nullable();        // one per line
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // Legal pages become ordinary builder pages (rich-text sections).
        Schema::dropIfExists('pages');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
        Schema::dropIfExists('section_items');
        Schema::dropIfExists('sections');
        Schema::dropIfExists('site_pages');

        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->longText('body')->nullable();
            $table->timestamps();
        });
    }
};
