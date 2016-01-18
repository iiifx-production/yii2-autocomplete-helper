<?php
/**
 * @author  Vitaliy IIIFX Khomenko (c) 2016
 * @license MIT
 *
 * @link    https://github.com/iiifx-production/yii2-autocomplete-helper
 */

namespace iiifx\Yii2\Autocomplete;

class Builder
{
    /**
     * @var Reader
     */
    protected $reader;

    /**
     * @var string
     */
    protected $filename = 'autocomplete.php';

    /**
     * @param string $params
     */
    public function __construct ( $params )
    {
        if ( isset( $params[ 'reader' ] ) && $params[ 'reader' ] instanceof Reader ) {
            $this->reader = $params[ 'reader' ];
        } else {
            throw new \InvalidArgumentException( 'Parameter "reader" is not found or type mismatch.' );
        }
    }

    /**
     * @param string $filepath
     *
     * @return bool
     */
    public function build ( $filepath )
    {
        if ( ( $this->reader->isValid() && is_dir( $this->reader->getPath() ) ) ) {
            $prepared = preg_replace_callback( '/%.*%/U', function ( $m ) {
                if ( $m[ 0 ] === '%phpdoc%' ) {
                    $string = '/**';
                    foreach ( $this->reader->getComponents() as $name => $classes ) {
                        $string .= PHP_EOL . ' * @property ' . implode( '|', $classes ) . ' $' . $name;
                    }
                    $string .= PHP_EOL . ' */';

                    return $string;
                }
            }, require( __DIR__ . '/template.php' ) );

            return (bool) file_put_contents( $filepath, $prepared );
        }

        return false;
    }
}
