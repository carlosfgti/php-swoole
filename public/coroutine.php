<?php
if (!extension_loaded('swoole')) {
    die("Swoole extension is not installed.\n");
}

Co\run(function()
{
    go(function()
    {
        Co::sleep(6);
        echo "Done 1\n";
    });

    go(function()
    {
        Co::sleep(5);
        echo "Done 2\n";
    });

    go(function()
    {
        Co::sleep(5);
        echo "Done 3\n";
    });

    go(function()
    {
        Co::sleep(5);
        echo "Done 4\n";
    });
});
