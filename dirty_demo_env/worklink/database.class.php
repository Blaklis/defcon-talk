<?php

class Database {
    private $dom;
    private $file;
    public function __construct($type) {
        $this->file = 'db/'.$type.'.php';
        $dom = new DOMDocument();
        $dom->load($this->file);

        // prevent xInclude, just in case!
        $dom->xinclude();
        $this->dom = $dom;
    }

    public function read($xpath) {
        $domxpath = new DOMXPath($this->dom);
        $elements = $domxpath->query($xpath);
        return $elements;
    }

    public function delete($node){
        $this->dom->documentElement->removeChild($node);
    }

    public function writeLine($tag, $attributes) {
        $line = "<$tag ";
        foreach ($attributes as $attribute => $value) {
            $line .= "$attribute=\"$value\" ";
        }
        $line .= "></$tag>";

        $node = clone $this->dom;
        $node->loadXML("<root>$line</root>");

        $this->dom->documentElement->appendChild($this->dom->importNode($node->documentElement->firstElementChild, true));
        $this->dom->save($this->file);
    }
}

