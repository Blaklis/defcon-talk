<?php
use Laminas\Mime\Mime;
use Laminas\Mime\Part;


class Message
{

    protected $zendMessage;

    private $messageType = Mime::TYPE_TEXT;

    public function __construct($charset = 'utf-8')
    {
        $this->zendMessage = new \Laminas\Mail\Message();
        $this->zendMessage->setEncoding($charset);
    }

    public function setMessageType($type)
    {
        $this->messageType = $type;
        return $this;
    }

    public function setBody($body)
    {
        if (is_string($body)) {
            $body = self::createMimeFromString($body, $this->messageType);
        }
        $this->zendMessage->setBody($body);
        return $this;
    }

    public function setSubject($subject)
    {
        $this->zendMessage->setSubject($subject);
        return $this;
    }

    public function getSubject()
    {
        return $this->zendMessage->getSubject();
    }

    public function getBody()
    {
        return $this->zendMessage->getBody();
    }

    public function setFrom($fromAddress)
    {
        $this->setFromAddress($fromAddress, null);
        return $this;
    }

    public function setFromAddress($fromAddress, $fromName = null)
    {
        $this->zendMessage->setFrom($fromAddress, $fromName);
        return $this;
    }

    public function addTo($toAddress)
    {
        $this->zendMessage->addTo($toAddress);
        return $this;
    }

    public function addCc($ccAddress)
    {
        $this->zendMessage->addCc($ccAddress);
        return $this;
    }

    public function addBcc($bccAddress)
    {
        $this->zendMessage->addBcc($bccAddress);
        return $this;
    }

    public function setReplyTo($replyToAddress)
    {
        $this->zendMessage->setReplyTo($replyToAddress);
        return $this;
    }

    public function getRawMessage()
    {
        return $this->zendMessage->toString();
    }

    private function createMimeFromString($body, $messageType)
    {
        $part = new Part($body);
        $part->setCharset($this->zendMessage->getEncoding());
        $part->setEncoding(Mime::ENCODING_QUOTEDPRINTABLE);
        $part->setDisposition(Mime::DISPOSITION_INLINE);
        $part->setType($messageType);
        $mimeMessage = new \Laminas\Mime\Message();
        $mimeMessage->addPart($part);
        return $mimeMessage;
    }

    public function setBodyHtml($html)
    {
        $this->setMessageType(Mime::TYPE_HTML);
        return $this->setBody($html);
    }

    public function setBodyText($text)
    {
        $this->setMessageType(Mime::TYPE_TEXT);
        return $this->setBody($text);
    }
}
