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
