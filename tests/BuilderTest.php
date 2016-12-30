<?php

require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

use iiifx\Yii2\Autocomplete\Builder;

class BuilderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function getData ()
    {
        $eol = PHP_EOL;
        return [
            # $template, $components, $result
            [ '%fail%', [ 'a' => [ 'A' => 'A' ] ], '%fail%' ],
            [ '%phpdoc%', [ 'a' => [ 'A' => 'A' ] ], "/**{$eol} * @property A \$a{$eol} */" ],
            [ '%phpdoc%', [ 'b' => [ 'B1' => 'B1', 'B2' => 'B2' ] ], "/**{$eol} * @property B1|B2 \$b{$eol} */" ],
            [ '%phpdoc%', [ 'c' => [ 'C1' => 'C1', 'C2' => 'C2', 'C3' => 'C3' ] ], "/**{$eol} * @property C1|C2|C3 \$c{$eol} */" ],
            [ '%phpdoc%', [ 'a' => [ 'A' => 'A' ], 'b' => [ 'B1' => 'B1', 'B2' => 'B2' ] ], "/**{$eol} * @property A \$a{$eol} * @property B1|B2 \$b{$eol} */" ],
        ];
    }

    /**
     * @dataProvider getData
     */
    public function testString ( $template, $components, $result )
    {
        $builder = new Builder( [
            'template' => $template,
            'components' => $components,
        ] );
        $this->assertEquals( $result, $builder->build( null ) );
        $builder->build( __DIR__ . '/temp/' . microtime( true ) . '.tmp' );
    }
}
