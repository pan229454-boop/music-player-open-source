<?php
/** Run from project root: php scripts/upgrade_music_source.php
 * Back up database first. No rows are deleted or rewritten.
 */
if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('CLI only');
}
try {
    require dirname(__DIR__) . '/vendor/autoload.php';
    $app = new \think\App(dirname(__DIR__));
    $app->initialize();
    $connection = \think\facade\Db::connect();
    $prefix = $connection->getConfig('prefix');
    $table = $prefix . 'song';
    if (!preg_match('/^[A-Za-z0-9_]+$/D', $table)) throw new \RuntimeException('Unsupported table name');
    $columns = $connection->query('SHOW COLUMNS FROM `' . $table . '`');
    foreach ($columns as $column) {
        if ($column['Field'] === 'music_source') {
            if (strtolower($column['Type']) !== 'varchar(64)' || $column['Default'] !== 'legacy') {
                throw new \RuntimeException('Existing music_source column differs from expected schema; inspect manually');
            }
            echo "music_source already exists; nothing changed.\n";
            exit(0);
        }
    }
    $connection->execute("ALTER TABLE `" . $table . "` ADD COLUMN `music_source` varchar(64) NOT NULL DEFAULT 'legacy' COMMENT 'Music API source identifier'");
    echo "Upgrade complete. Existing songs default to legacy; no songs deleted.\n";
} catch (\Throwable $e) {
    fwrite(STDERR, "Upgrade failed. Check database connectivity, ALTER permission and schema locally.\n");
    exit(1);
}
