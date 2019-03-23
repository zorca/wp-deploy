<?php
namespace Deployer;

require 'recipe/common.php';

// Load config
inventory('deploy.yml');

// Bedrock shared dirs
set('shared_dirs', [
    'web/app/uploads',
]);

// Bedrock shared files
set('shared_files', [
    '.env',
]);

// Bedrock writable dirs
set('writable_dirs', [
    'web/app/uploads',
]);

// Tasks

/**
 * Test task
 */
task('test', function () {
    $result = run('pwd');
    writeln("Current dir: $result");
});

/**
 * Push database task
 */
task('push:db', function () {
    runLocally('wp db export --add-drop-table current.sql');
    upload('current.sql', '{{release_path}}/current.sql');
    run('cd {{release_path}} && wp db import current.sql');
    run('cd {{release_path}} && wp search-replace "//'.get('domain_local').'" "//'.get('domain_remote').'"');
});

/**
 * Main task
 */
desc('Deploy your project');
task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:vendors',
    'deploy:writable',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
]);

after('deploy', 'success');

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');
