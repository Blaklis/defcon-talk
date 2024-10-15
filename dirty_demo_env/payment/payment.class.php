<?php

class Payment {
    function __construct($order_id, $cc, $exp, $cvv) {
        $this->order_id = $order_id;
        $this->cc = $cc;
        $this->exp = $exp;
        $this->cvv = $cvv;
        $this->state = "pending";
        $this->debug = false;
        $this->debug();
    }

    function checkPayment() {
        if($this->cc == "1111 1111 1111 1111") {
            $this->state = "success";
        } else {
            $this->state = "denied";
        }
        return $this->state;
    }

    function debug(){
        file_put_contents('/tmp/'.$this->order_id, serialize($this));
    }


    function __destruct(){
        if(!$this->debug) {
            unlink('/tmp/' . $this->order_id);
        }
    }
}