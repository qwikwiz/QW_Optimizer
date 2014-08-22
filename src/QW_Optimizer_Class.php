<?php
/*
 * QW_Optimizer by Ariel Henryson
 */

class QW_Optimizer {
    private $src;
    private $dom;

    function __construct($src) {
        $this->src = file_get_contents($src);

        if(!$this->src) {
            echo json_encode(array(
                'status'     => 'false',
                'error'      => 'cant load file',
                'error_code' => 1
            ));

            exit;
        }

        $this->dom = new DOMDocument();
        $this->dom->loadHTML($this->src);
    }

    public function inline_script(){
        $elements = $this->dom->getElementsByTagName('script');
        $i = $elements->length - 1;
        while ($i > -1) {
            $el = $elements->item($i);
            if ($el->hasAttribute('src') && !$el->hasAttribute('data-skip')) {
                $src = $el->getAttribute('src');
                $script_content = file_get_contents($src);
                $script = $this->dom->createElement("script", $script_content);
                $el->parentNode->replaceChild($script, $el);
            }

            $i--;
        }
    }

    public function get_html() {
        return $this->dom->saveHTML();
    }
}