<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddTemplateExample extends Migration
{

    // Parameters adding tv
    public $template_templatename = 'Test';
    public $template_templatealias = 'test';
    public $template_tvs = array(1, 2);
    public $template_category = 0;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // WARNING: this code will remove the template by name and its binding to tv
        $this->down();

        // Adding a template and binding it to tv
        $new_template_id = DB::table('site_templates')->insertGetId([
            'templatename' => $this->template_templatename,
            'templatealias' => $this->template_templatealias,
            'description' => '',
            'category' => $this->template_category,
            'content' => '',
            'createdon' => time(),
            'editedon' => time(),
        ]);
        if (count($this->template_tvs)) {
            foreach ($this->template_tvs as $template_tv) {
                DB::table('site_tmplvar_templates')->insert([
                    'tmplvarid' => $template_tv,
                    'templateid' => $new_template_id,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Removing a template and its bindings to tv
        $old_templates = DB::table('site_templates')
            ->select('id')
            ->where('templatename', '=', $this->template_templatename)
            ->get();
        if (count($old_templates)) {
            foreach ($old_templates as $old_template) {
                DB::table('site_tmplvar_templates')
                    ->where('templateid', '=', $old_template->id)
                    ->delete();
                DB::table('site_templates')
                    ->where('id', '=', $old_template->id)
                    ->delete();
            }
        }
    }
}
