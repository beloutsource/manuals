<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Config\Config;

class AddFieldExample extends AbstractMigration
{
    // Parameters adding field and chunk
    public $field_table = 'site_content';
    public $field_nametype = array('test', 'string');
    public $field_index = 1;

    /**
     * Get config parameter.
     *
     * @return string
     */
    public function getEnv($param) {
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
        // WARNING: this code will delete the field with the index
        $this->down();

        // Adding a field and index
        $table = $this->table($this->field_table);
        $table->addColumn($this->field_nametype[0], $this->field_nametype[1]);
        if ($this->field_index == 1) {
            $table->addIndex(array($this->field_nametype[0]));
        }
        $table->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Removing a field and index
        $table = $this->table($this->field_table);
        if ($table->hasColumn($this->field_nametype[0])) {
            $table->removeColumn($this->field_nametype[0]);
            if ($this->field_index == 1) {
                $table->removeIndex(array($this->field_nametype[0]));
            }
            $table->update();
        }
    }
}