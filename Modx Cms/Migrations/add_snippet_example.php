<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Config\Config;

class AddSnippetExample extends AbstractMigration
{
    // Parameters adding snippet
    public $snippet_name = 'test';
    public $snippet_description = 'Test';
    public $snippet_category = 0;
    public $snippet_static = 1;
    public $snippet_file = 'core/elements/snippets/test/test.snippet.php';

    /**
     * Get config parameter.
     *
     * @return string
     */
    public function getEnv($param)
    {
        $config = Config::fromPhp(dirname(dirname(__FILE__)) . '/phinx.php');
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
        // WARNING: this code will remove the snippet with the given name
        $this->down();

        // Adding a sniipet
        $this->getQueryBuilder()
            ->insert(['source', 'name', 'description', 'category', 'snippet', 'properties', 'static', 'static_file'])
            ->into($this->getEnv('table_prefix') . 'site_snippets')
            ->values(
                [
                    'source' => 1,
                    'name' => $this->snippet_name,
                    'description' => $this->snippet_description,
                    'category' => $this->snippet_category,
                    'snippet' => '',
                    'properties' => 'a:0:{}',
                    'static' => $this->snippet_static,
                    'static_file' => $this->snippet_file
                ]
            )
            ->execute();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Removing a snippet
        $this->getQueryBuilder()
            ->delete($this->getEnv('table_prefix') . 'site_snippets')
            ->where(['name' => $this->snippet_name])
            ->execute();
    }
}