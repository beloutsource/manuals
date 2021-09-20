<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Config\Config;

class AddPluginExample extends AbstractMigration
{
    // Parameters adding plugin
    public $plugin_name = 'test';
    public $plugin_description = 'Test';
    public $plugin_events = array(1, 2);
    public $plugin_category = 0;
    public $plugin_static = 1;
    public $plugin_file = 'core/elements/plugins/test/test.plugin.php';

    /**
     * Get config parameter.
     *
     * @return string
     */
    public function getEnv($param)
    {
        $config = Config::fromYaml(dirname(dirname(dirname(__FILE__))) . '/phinx.yml');
        $env = $config->getEnvironment($config->getDefaultEnvironment());
        if (isset($env[$param])) {
            return $env[$param];
        }
        return '';
    }

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
        $statement = $this->getQueryBuilder()
            ->insert(['source', 'name', 'description', 'category', 'plugincode', 'properties', 'static', 'static_file'])
            ->into($this->getEnv('table_prefix') . 'site_plugins')
            ->values(
                [
                    'source' => 1,
                    'name' => $this->plugin_name,
                    'description' => $this->plugin_description,
                    'category' => $this->plugin_category,
                    'plugincode' => '',
                    'properties' => 'a:0:{}',
                    'static' => $this->plugin_static,
                    'static_file' => $this->plugin_file
                ]
            )
            ->execute();
        $plugin_id = $statement->lastInsertId();
        if (count($this->plugin_events)) {
            foreach ($this->plugin_events as $plugin_event) {
                $this->getQueryBuilder()
                    ->insert(['pluginid', 'event', 'priority'])
                    ->into($this->getEnv('table_prefix') . 'site_plugin_events')
                    ->values(
                        [
                            'pluginid' => $plugin_id,
                            'event' => $plugin_event,
                            'priority' => 0
                        ]
                    )
                    ->execute();
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
        $statement = $this->getQueryBuilder()
            ->select('id')
            ->from($this->getEnv('table_prefix') . 'site_plugins')
            ->where(['name' => $this->plugin_name])
            ->execute();
        $res = $statement->fetchAll();
        if (isset($res[0][0])) {
            $plugin_id = $res[0][0];
            $this->getQueryBuilder()
                ->delete($this->getEnv('table_prefix') . 'site_plugins')
                ->where(['name' => $this->plugin_name])
                ->execute();
            $this->getQueryBuilder()
                ->delete($this->getEnv('table_prefix') . 'site_plugin_events')
                ->where(['pluginid' => $plugin_id])
                ->execute();
        }
    }
}