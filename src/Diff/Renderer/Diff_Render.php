<?php
namespace Sistacad\Diff\Renderer;

use Phalcon\Diff\Renderer\AbstractRender;

class Diff_Render extends AbstractRender
{
    /**
     * The "old" sequence to use as the basis for the comparison.
     * @var array
     */
    public $a = null;

    /**
     * The "new" sequence to generate the changes for.
     * @var array
     */
    public $b = null;
    public function render()
    {
        $this->a = $this->diff->getA();
        $this->b = $this->diff->getB();
        $opCodes = $this->diff->getGroupedOpcodes();

        foreach($opCodes as $group) {
            $lastItem = count($group)-1;
            $i1 = $group[0][1];
            $i2 = $group[$lastItem][2];
            $j1 = $group[0][3];
            $j2 = $group[$lastItem][4];

            if($i1 == 0 && $i2 == 0) {
                $i1 = -1;
                $i2 = -1;
            }

            foreach($group as $code) {
                list($tag, $i1, $i2, $j1, $j2) = $code;
                if($tag != 'equal') {
                    for($i = $j1; $i< $j2; $i++) {
                        $this->b[$i] = preg_replace('/(background-color:#.{6})/', '', $this->b[$i]);
                        if(strpos($this->b[$i], 'style')) {
                            $this->b[$i] = str_replace('style="', 'style="'.$this->options['style'], $this->b[$i]);
                        } else {
                            $this->b[$i] = str_replace('<td', '<td style="'.$this->options['style'].'"', $this->b[$i]);
                        }
                    }
                }
            }
        }
        return implode("\n", $this->b);
    }
}