<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddTvExample extends Migration
{

    // Parameters adding tv
    public $tv_name = 'test';
    public $tv_caption = 'Test';
    public $tv_templates = array(1, 2);
    public $tv_roles = array(1, 2);
    public $tv_category = 0;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // WARNING: this code will delete all data old data about tv with given name from db, including resource data
        $this->down();

        // Adding tv, and its links to tables and roles
        $new_tv_id = DB::table('site_tmplvars')->insertGetId([
            'type' => 'text',
            'name' => $this->tv_name,
            'caption' => $this->tv_caption,
            'category' => $this->tv_category,
            'elements' => '',
            'display_params' => '',
            'default_text' => '',
            'createdon' => time(),
            'editedon' => time(),
        ]);
        if (count($this->tv_templates)) {
            foreach ($this->tv_templates as $tv_template) {
                DB::table('site_tmplvar_templates')->insert([
                    'tmplvarid' => $new_tv_id,
                    'templateid' => $tv_template,
                ]);
            }
        }
        if (count($this->tv_roles)) {
            foreach ($this->tv_roles as $tv_role) {
                DB::table('user_role_vars')->insert([
                    'tmplvarid' => $new_tv_id,
                    'roleid' => $tv_role,K
                    'rank' => 0,
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
        // Deleting old tv, data from tv link table with templates, roles, and the values given to it from resources
        $old_tvs = DB::table('site_tmplvars')
            ->select('id')
            ->where('name', '=', $this->tv_name)
            ->get();
        if (count($old_tvs)) {
            foreach ($old_tvs as $old_tv) {
                DB::table('site_tmplvar_contentvalues')
                    ->where('tmplvarid', '=', $old_tv->id)
                    ->delete();
                DB::table('site_tmplvar_templates')
                    ->where('tmplvarid', '=', $old_tv->id)
                    ->delete();
                DB::table('user_role_vars')
                    ->where('tmplvarid', '=', $old_tv->id)
                    ->delete();
                DB::table('site_tmplvars')
                    ->where('id', '=', $old_tv->id)
                    ->delete();
            }
        }
    }
}
