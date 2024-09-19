<?php

/**
 * Ecosystème PHP d'Orange
 *
 * Copyright (C) 2020  Orange / CCPHP (ZZZ CDC PHP <cdc.php@orange.com>)
 *
 * This software is confidential and proprietary information of Orange.
 * You shall not disclose such Confidential Information and shall use it only in
 * accordance with the terms of the agreement you entered into.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 * If you are Orange employee you shall use this software in accordance with
 * the Orange Source Charter (http://opensource.itn.ftgroup/index.php/Orange_Source_Charter)
 */

use CfCommunity\CfHelper\CfHelper;

// Customize here the parts of application's services names
if (!defined('DATABASE_SERVICE_NAME_REGEX')) {
    define('DATABASE_SERVICE_NAME_REGEX', '.*database.*');
}
if (!defined('CACHE_SERVICE_NAME_REGEX')) {
    define('CACHE_SERVICE_NAME_REGEX', '.*redis-cache.*');
}
if (!defined('STORAGE_SERVICE_NAME_REGEX')) {
    define('STORAGE_SERVICE_NAME_REGEX', '.*storage.*');
}

// CloudFoundry PHP helper
$cfHelper = new CfHelper();

// Handle a non-Cloudfoundry environment
if ($cfHelper->isInCloudFoundry() === false) {
    // Prevent a wrong usage of this file...
    throw new RuntimeException("Configuration file config/cloudfoundry.php should not be used because this environment does not look like Cloudfoundry. Please check your configuration. You also can remove this exception to simulate CF services.");
    // .. but we also can define default values if it is usefull to simulate CF services, for example :
    //$container->setParameter('database_cf_url', $_SERVER['DATABASE_URL']);
    //$container->setParameter('redis_cf_url', $_SERVER['REDIS_URL']);
    //return;
}

// Find the application's services configurations
$services = $cfHelper->getServiceManager();
$database = $services->getService(DATABASE_SERVICE_NAME_REGEX);
$redis = $services->getService(CACHE_SERVICE_NAME_REGEX);
$storage = $services->getService(STORAGE_SERVICE_NAME_REGEX);

// Throw a technical error if one of the services is not found
if ($database === null || $redis === null) {
    throw new RuntimeException("Cloudfoundry's services were not found, please check the constants values defined in config/cloudfoundry.php. Are they matching your services names ?");
}

// Define the application parameters
// - For database service
$container->setParameter('database_cf_url', $database->getValue('uri'));
// - For Redis cache service
$container->setParameter('redis_cf_url', 'redis://' . $redis->getValue('password') . '@' . $redis->getValue('host') . ':' . $redis->getValue('port'));
// - For storage service
$container->setParameter('storage_cf_host', $storage->getValue('host'));
$container->setParameter('storage_cf_bucket', $storage->getValue('bucket'));
$container->setParameter('storage_cf_access_key_id', $storage->getValue('access_key_id'));
$container->setParameter('storage_cf_secret_access_key', $storage->getValue('secret_access_key'));
