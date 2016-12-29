<?php
/**
 * @author  Vitaliy IIIFX Khomenko (c) 2016
 * @license MIT
 *
 * @link    https://github.com/iiifx-production/yii2-autocomplete-helper
 */

namespace iiifx\Yii2\Autocomplete;

class Builder extends \yii\base\Object
{
    /**
     * @var string
     */
    public $template;

    /**
     * @var array
     */
    public $components = [];

    /**
     * @param string $file
     *
     * @return bool
     */
    public function build ( $file )
    {
        $prepared = preg_replace_callback( '/%.*%/U', function ( $m ) {
            if ( $m[ 0 ] === '%phpdoc%' ) {
                $string = '/**';
                foreach ( $this->components as $name => $classes ) {
                    $string .= PHP_EOL . ' * @property ' . implode( '|', $classes ) . ' $' . $name;
                }
                $string .= PHP_EOL . ' */';
                return $string;
            }
            return $m[ 0 ];
        }, $this->template );
        return (bool) file_put_contents( $file, $prepared );
    }
}
