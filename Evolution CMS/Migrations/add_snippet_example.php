<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddSnippetExample extends Migration
{

    // Parameters adding snippet
    public $snippet_name = 'test';
    public $snippet_description = 'Test';
    public $snippet_category = 0;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // WARNING: this code will remove the snippet with the given name
        $this->down();

        // Adding a sniipet
        $new_sniipet_id = DB::table('site_snippets')->insertGetId([
            'name' => $this->snippet_name,
            'description' => $this->snippet_description,
            'category' => $this->snippet_category,
            'snippet' => '',
            'properties' => '{}',
            'createdon' => time(),
            'editedon' => time(),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Removing a snippet
        $old_snippets = DB::table('site_snippets')
            ->select('id')
            ->where('name', '=', $this->snippet_name)
            ->get();
        if (count($old_snippets)) {
            foreach ($old_snippets as $old_snippet) {
                DB::table('site_snippets')
                    ->where('id', '=', $old_snippet->id)
                    ->delete();
            }
        }
    }
}
