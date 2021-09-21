<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Config\Config;

class AddChunkExample extends AbstractMigration
{
    // Parameters adding chunk
    public $chunk_name = 'test.tpl';
    public $chunk_description = 'Test';
    public $chunk_category = 0;
    public $chunk_static = 1;
    public $chunk_file = 'core/elements/chunks/test/test.chunk.tpl';

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
        // WARNING: this code will delete the chunk with the given name
        $this->down();

        // Adding a chunk
        $this->getQueryBuilder()
            ->insert(['source', 'name', 'description', 'category', 'snippet', 'properties', 'static', 'static_file'])
            ->into($this->getEnv('table_prefix') . 'site_htmlsnippets')
            ->values(
                [
                    'source' => 1,
                    'name' => $this->chunk_name,
                    'description' => $this->chunk_description,
                    'category' => $this->chunk_category,
                    'snippet' => '',
                    'properties' => 'a:0:{}',
                    'static' => $this->chunk_static,
                    'static_file' => $this->chunk_file
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
        // Removing a chunk
        $this->getQueryBuilder()
            ->delete($this->getEnv('table_prefix') . 'site_htmlsnippets')
            ->where(['name' => $this->chunk_name])
            ->execute();
    }
}