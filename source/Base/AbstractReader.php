<?php

namespace iiifx\Yii2\Autocomplete\Base;

abstract class AbstractReader
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @var mixed[]
     */
    protected $schema = [
        'config/web.php',
        'config/console.php',
    ];

    /**
     * @var mixed[]
     */
    protected $config;

    /**
     * @var mixed[]
     */
    protected $components;

    /**
     * @param string $path
     */
    public function __construct ( $path )
    {
        $this->path = $path;
    }

    /**
     * @return bool
     */
    public function isValid ()
    {
        if ( $this->components === null ) {
            if ( ( $configData = $this->getConfig() ) ) {
                $this->components = $this->getComponents( $configData );
            }
        }

        return (bool) $this->components;
    }

    /**
     * @return \mixed[]|void
     */
    public function getConfig ()
    {
        if ( $this->config === null ) {
            $this->config = $this->readConfig();
        }

        return $this->config;
    }

    /* protected */ function readConfig ()
    {
        $path = rtrim( $this->path, '/\\' );
        if ( is_dir( $path ) ) {
            $config = [ ];
            foreach ( $this->schema as $file ) {
                $file = DIRECTORY_SEPARATOR . ltrim( $file, '/\\' );
                if ( is_file( $path . $file ) ) {
                    /** @noinspection PhpIncludeInspection */
                    $data = require( $path . $file );
                    var_export( $data ); die();
                }
            }
        }
    }

    //public function getComponents ( array $configData );
}
