<?php
/* (c) Anton Medvedev <anton@medv.io>
 *
 */

namespace Deployer;

desc('Creating symlink to release');
task('deploy:symlink', function () {
    if (get('use_atomic_symlink')) {
        run("mv -T {{deploy_path}}/release {{deploy_path}}/current");
    } else {
        // Atomic symlink does not supported.
        // Will use simple≤ two steps switch.

        run("cd {{deploy_path}} && {{bin/symlink}} {{release_path}} current"); // Atomic override symlink.
        run("cd {{deploy_path}} && rm release"); // Remove release link.
    }
});