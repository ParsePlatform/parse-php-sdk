<?php
/**
 * Created by PhpStorm.
 * User: Bfriedman
 * Date: 10/30/17
 * Time: 16:35
 */

namespace Parse\Test;


use Parse\ParseServerInfo;

class ParseServerInfoTest extends \PHPUnit_Framework_TestCase
{
    public function testDirectGet()
    {
        $logs = ParseServerInfo::get('logs');
        $this->assertNotNull($logs);
    }

    public function testGetFeatures()
    {
        $features = ParseServerInfo::getFeatures();
        $this->assertNotEmpty($features);
    }

    public function testGetVersion()
    {
        $version = ParseServerInfo::getVersion();
        $this->assertNotNull($version);
    }

    public function testGlobalConfigFeatures()
    {
        $globalConfigFeatures = ParseServerInfo::getGlobalConfigFeatures();
        $this->assertTrue($globalConfigFeatures['create']);
        $this->assertTrue($globalConfigFeatures['read']);
        $this->assertTrue($globalConfigFeatures['update']);
        $this->assertTrue($globalConfigFeatures['delete']);
    }

    public function testHooksFeatures()
    {
        $hooksFeatures = ParseServerInfo::getHooksFeatures();
        $this->assertTrue($hooksFeatures['create']);
        $this->assertTrue($hooksFeatures['read']);
        $this->assertTrue($hooksFeatures['update']);
        $this->assertTrue($hooksFeatures['delete']);
    }

    public function testCloudCodeFeatures()
    {
        $cloudCodeFeatures = ParseServerInfo::getCloudCodeFeatures();
        $this->assertTrue($cloudCodeFeatures['jobs']);
    }

    public function testLogsFeatures()
    {
        $logsFeatures = ParseServerInfo::getLogsFeatures();
        $this->assertTrue($logsFeatures['level']);
        $this->assertTrue($logsFeatures['size']);
        $this->assertTrue($logsFeatures['order']);
        $this->assertTrue($logsFeatures['until']);
        $this->assertTrue($logsFeatures['from']);
    }

    public function testPushFeatures()
    {
        $pushFeatures = ParseServerInfo::getPushFeatures();

        // these may change depending on the server being tested against
        $this->assertTrue(isset($pushFeatures['immediatePush']));
        $this->assertTrue(isset($pushFeatures['scheduledPush']));
        $this->assertTrue(isset($pushFeatures['storedPushData']));

        $this->assertTrue($pushFeatures['pushAudiences']);
        $this->assertTrue($pushFeatures['localization']);
    }

    public function testSchemasFeatures()
    {
        $schemasFeatures = ParseServerInfo::getSchemasFeatures();
        $this->assertTrue($schemasFeatures['addField']);
        $this->assertTrue($schemasFeatures['removeField']);
        $this->assertTrue($schemasFeatures['addClass']);
        $this->assertTrue($schemasFeatures['removeClass']);
        $this->assertTrue($schemasFeatures['clearAllDataFromClass']);
        $this->assertFalse($schemasFeatures['exportClass']);
        $this->assertTrue($schemasFeatures['editClassLevelPermissions']);
        $this->assertTrue($schemasFeatures['editPointerPermissions']);
    }

}