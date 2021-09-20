<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Config\Config;

class AddTvExample extends AbstractMigration
{
    // Parameters adding tv
    public $tv_type = 'text';
    public $tv_name = 'test';
    public $tv_caption = 'Test';
    public $tv_description = 'Test';
    public $tv_templates = array(1, 2);
    public $tv_documents = array(1, 2);
    public $tv_category = 0;
    public $tv_rank = 0;
    public $tv_display = 'default';
    public $tv_static = 1;
    public $tv_file = 'core/elements/tvs/test/test.tv.tpl';

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
        // WARNING: this code will delete all data old data about tv with given name from db, including resource data
        $this->down();

        // Adding tv, and its links to tables and documentgroups
        $statement = $this->getQueryBuilder()
            ->insert(['source', 'type', 'name', 'caption', 'description', 'category', 'elements', 'rank', 'display',
                'default_text', 'properties', 'input_properties', 'output_properties', 'static', 'static_file'])
            ->into($this->getEnv('table_prefix') . 'site_tmplvars')
            ->values(
                [
                    'source' => 1,
                    'type' => $this->tv_type,
                    'name' => $this->tv_name,
                    'caption' => $this->tv_caption,
                    'description' => $this->tv_description,
                    'category' => $this->tv_category,
                    'elements' => '',
                    'rank' => $this->tv_rank,
                    'display' => $this->tv_display,
                    'default_text' => '',
                    'properties' => 'a:0:{}',
                    'input_properties' => 'a:0:{}',
                    'output_properties' => 'a:0:{}',
                    'static' => $this->tv_static,
                    'static_file' => $this->tv_file
                ]
            )
            ->execute();
        $tv_id = $statement->lastInsertId();
        if (count($this->tv_templates)) {
            foreach ($this->tv_templates as $tv_template) {
                $this->getQueryBuilder()
                    ->insert(['tmplvarid', 'templateid'])
                    ->into($this->getEnv('table_prefix') . 'site_tmplvar_templates')
                    ->values(
                        [
                            'tmplvarid' => $tv_id,
                            'templateid' => $tv_template
                        ]
                    )
                    ->execute();
            }
        }
        if (count($this->tv_documents)) {
            foreach ($this->tv_documents as $tv_document) {
                $this->getQueryBuilder()
                    ->insert(['tmplvarid', 'documentgroup'])
                    ->into($this->getEnv('table_prefix') . 'site_tmplvar_access')
                    ->values(
                        [
                            'tmplvarid' => $tv_id,
                            'documentgroup' => $tv_document
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
        // Deleting old tv, data from tv link table with templates, documentgroups, and the values given to it
        // from resources
        $statement = $this->getQueryBuilder()
            ->select('id')
            ->from($this->getEnv('table_prefix') . 'site_tmplvars')
            ->where(['name' => $this->tv_name])
            ->execute();
        $res = $statement->fetchAll();
        if (isset($res[0][0])) {
            $tv_id = $res[0][0];
            $this->getQueryBuilder()
                ->delete($this->getEnv('table_prefix') . 'site_tmplvars')
                ->where(['name' => $this->tv_name])
                ->execute();
            $this->getQueryBuilder()
                ->delete($this->getEnv('table_prefix') . 'site_tmplvar_contentvalues')
                ->where(['tmplvarid' => $tv_id])
                ->execute();
            $this->getQueryBuilder()
                ->delete($this->getEnv('table_prefix') . 'site_tmplvar_templates')
                ->where(['tmplvarid' => $tv_id])
                ->execute();
            $this->getQueryBuilder()
                ->delete($this->getEnv('table_prefix') . 'site_tmplvar_access')
                ->where(['tmplvarid' => $tv_id])
                ->execute();
        }
    }
}