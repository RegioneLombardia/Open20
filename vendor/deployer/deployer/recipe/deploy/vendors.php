<?php
/* (c) Anton Medvedev <anton@medv.io>
 *
 */

namespace Deployer;

desc('Installing vendors');
task('deploy:vendors', function () {
    if (!commandExist('unzip')) {
        writeln('<comment>To speed up composer installation setup "unzip" command with PHP zip extension https://goo.gl/sxzFcD</comment>');
    }
    run('cd {{release_path}} && {{bin/composer}} {{composer_options}}');
});
