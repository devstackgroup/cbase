<?php
require_once('AbstractDatabaseTestCase.php');

class YamlTestCase extends AbstractDatabaseTestCase
{
    protected function getDataSet()
    {
        return new PHPUnit_Extensions_Database_DataSet_YamlDataSet(
            dirname(__FILE__) . '/fixtures/' . $GLOBALS['YAML_FILE']
        );
    }
}