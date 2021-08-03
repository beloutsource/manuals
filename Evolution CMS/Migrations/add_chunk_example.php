<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddChunkExample extends Migration
{

    // Parameters adding chunk
    public $chunk_name = 'test';
    public $chunk_description = 'Test';
    public $chunk_category = 0;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // WARNING: this code will delete the chunk with the given name
        $this->down();

        // Adding a chunk
        $new_chunk_id = DB::table('site_htmlsnippets')->insertGetId([
            'name' => $this->chunk_name,
            'description' => $this->chunk_description,
            'category' => $this->chunk_category,
            'snippet' => '',
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
        // Removing a chunk
        $old_chunks = DB::table('site_htmlsnippets')
            ->select('id')
            ->where('name', '=', $this->chunk_name)
            ->get();
        if (count($old_chunks)) {
            foreach ($old_chunks as $old_chunk) {
                DB::table('site_htmlsnippets')
                    ->where('id', '=', $old_chunk->id)
                    ->delete();
            }
        }
    }
}
