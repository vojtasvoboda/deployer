<?php
namespace Inspirio\Deployer\Application;


class LazyCms2 implements ApplicationInterface {

    /**
     * @var string
     */
    private $appDir;

    /**
     * {@inheritdoc}
     */
    public function __construct($rootPath) {
        $this->appDir = realpath($rootPath);

        if ($this->appDir === false) {
            throw new \RuntimeException("Application directory '{$rootPath}' does not exist.");
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRootPath() {
        return $this->appDir;
    }

    /**
     * {@inheritdoc}
     */
    public function getDatabaseConnection() {
        $defaults = array(
            'host'     => 'localhost',
            'name'     => null,
            'user'     => null,
            'password' => null,
        );

        $configFile = $this->getConfigFile();
        $params  = $this->parseConfig($configFile);
        $params += $defaults;

        $dsn    = "mysql:host={$params['database_host']};dbname={$params['database_name']}";

        return new \PDO($dsn, $params['database_user'], $params['database_password']);
    }

    /**
     * Finds a config file.
     *
     * @return string|null
     */
    private function getConfigFile()
    {
        $fileNames = array(
            'settings/config-local.php',
            'settings/local-settings.php',
            'classes/db-auth.php',
        );

        foreach ($fileNames as $fileName) {
            if (file_exists($this->appDir .'/'. $fileName)) {
                return $this->appDir .'/'. $fileName;
            }
        }

        return null;
    }

    /**
     * Parses application config.
     *
     * @param string $filePath
     * @return array
     */
    private function parseConfig($filePath)
    {
        $content = file_get_contents($filePath);
        preg_match_all('/define\s*\\(\s*(?:\'|")([^\'"]+)(?:\'|")\s*,\s*(?:\'|")([^\'"]*)(?:\'|")\s*\\)\s*;/', $content, $matches, PREG_SET_ORDER);

        $config  = array();
        $mapping = array(
            'AUTH_DATABASE_NAME' => 'name',
            'HOST'               => 'host',
            'USER'               => 'user',
            'PASSWORD'           => 'password',
        );

        foreach ($matches as $match) {
            $symbol = $match[1];
            $value  = $match[2];

            if (!isset($mapping[$symbol])) {
                continue;
            }

            $config[$mapping[$symbol]] = $value;
        }

        return $config;
    }

    public function findFile($file)
    {
        // TODO: Implement findAppFile() method.
    }
}
