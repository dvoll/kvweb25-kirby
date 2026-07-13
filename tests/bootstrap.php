<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

$base = dirname(__DIR__);

define('TEST_TMP_DIR', $base . '/tests/tmp');
define('TEST_CONTENT_DIR', TEST_TMP_DIR . '/content');
define('TEST_STORAGE_DIR', TEST_TMP_DIR . '/storage');
define('TEST_ACCOUNTS_DIR', TEST_TMP_DIR . '/accounts');
define('TEST_CACHE_DIR', TEST_TMP_DIR . '/cache');
define('TEST_SESSIONS_DIR', TEST_TMP_DIR . '/sessions');
