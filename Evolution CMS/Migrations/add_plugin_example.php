<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddPluginExample extends Migration
{

    // Parameters adding plugin
    public $plugin_name = 'test';
    public $plugin_description = 'Test';
    public $plugin_events = array(1, 2);
    public $plugin_category = 0;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // WARNING: this code will remove the plugin with the given name and its event bindings
        $this->down();

        // Adding a plugin and binding it to events
        $new_plugin_id = DB::table('site_plugins')->insertGetId([
            'name' => $this->plugin_name,
            'description' => $this->plugin_description,
            'category' => $this->plugin_category,
            'plugincode' => '',
            'properties' => '',
            'createdon' => time(),
            'editedon' => time(),
        ]);
        if (count($this->plugin_events)) {
            foreach ($this->plugin_events as $plugin_event) {
                DB::table('site_plugin_events')->insert([
                    'pluginid' => $new_plugin_id,
                    'evtid' => $plugin_event,
                    'priority' => 0,
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
        // Removing a Template and Its Event Bindings
        $old_plugins = DB::table('site_plugins')
            ->select('id')
            ->where('name', '=', $this->plugin_name)
            ->get();
        if (count($old_plugins)) {
            foreach ($old_plugins as $old_plugin) {
                DB::table('site_plugin_events')
                    ->where('pluginid', '=', $old_plugin->id)
                    ->delete();
                DB::table('site_plugins')
                    ->where('id', '=', $old_plugin->id)
                    ->delete();
            }
        }
    }
}
