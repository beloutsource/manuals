<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Config\Config;

class AddTemplateExample extends AbstractMigration
{
    // Parameters adding template
    public $template_templatename = 'Test';
    public $template_description = 'Test';
    public $template_tvs = array(1, 2);
    public $template_category = 0;
    public $template_static = 1;
    public $template_file = 'core/elements/templates/test/test.template.tpl';

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
        // WARNING: this code will remove the template by name and its binding to tv
        $this->down();

        // Adding a template and binding it to tv
        $statement = $this->getQueryBuilder()
            ->insert(['source', 'templatename', 'description', 'category', 'content', 'properties', 'static',
                'static_file'])
            ->into($this->getEnv('table_prefix') . 'site_templates')
            ->values(
                [
                    'source' => 1,
                    'templatename' => $this->template_templatename,
                    'description' => $this->template_description,
                    'category' => $this->template_category,
                    'content' => '',
                    'properties' => 'a:0:{}',
                    'static' => $this->template_static,
                    'static_file' => $this->template_file
                ]
            )
            ->execute();
        $template_id = $statement->lastInsertId();
        if (count($this->template_tvs)) {
            foreach ($this->template_tvs as $template_tv) {
                $this->getQueryBuilder()
                    ->insert(['tmplvarid', 'templateid'])
                    ->into($this->getEnv('table_prefix') . 'site_tmplvar_templates')
                    ->values(
                        [
                            'tmplvarid' => $template_tv,
                            'templateid' => $template_id
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
        // Removing a template and its bindings to tv
        $statement = $this->getQueryBuilder()
            ->select('id')
            ->from($this->getEnv('table_prefix') . 'site_templates')
            ->where(['templatename' => $this->template_templatename])
            ->execute();
        $res = $statement->fetchAll();
        if (isset($res[0][0])) {
            $template_id = $res[0][0];
            $this->getQueryBuilder()
                ->delete($this->getEnv('table_prefix') . 'site_templates')
                ->where(['templatename' => $this->template_templatename])
                ->execute();
            $this->getQueryBuilder()
                ->delete($this->getEnv('table_prefix') . 'site_tmplvar_templates')
                ->where(['templateid' => $template_id])
                ->execute();
        }
    }
}