<?php
declare(strict_types=1);

system(
    __DIR__ . '/../../../mysql/bin/mysql --host=localhost --port=3307 mysql -u root < ' . __DIR__ . '/db.sql',
    $returnStatusCode
);

if ($returnStatusCode === 0) {
    echo 'Database created successfully.';
}