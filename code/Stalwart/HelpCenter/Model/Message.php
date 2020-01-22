<?php

namespace Stalwart\HelpCenter\Model;

class Message extends \Magento\Framework\Mail\Message {

	protected $_parts = [];

	public function setBodyText($content) {
		$textPart = new \Zend\Mime\Part();
		$textPart->setContent($content)
			->setType(\Zend\Mime\Mime::TYPE_TEXT)
			->setCharset('utf-8')
		;
		$this->_parts[] = $textPart;
		$this->setPartsToBody();
		return $this;
	}

	public function setBodyHtml($content) {
		$htmlPart = new \Zend\Mime\Part();
		$htmlPart->setContent($content)
			->setType(\Zend\Mime\Mime::TYPE_HTML)
			->setCharset('utf-8')
		;
		$this->_parts[] = $htmlPart;
		$this->setPartsToBody();
		return $this;
	}

	public function createAttachment($content, $fileType, $disposition, $encoding, $fileName) {
		if($content === null) throw new \Exception("Param 'content' can not be null");
		if($fileType === null) $fileType = 'application/pdf';
		if($disposition === null) $disposition = \Zend\Mime\Mime::DISPOSITION_ATTACHMENT;
		if($encoding === null) $encoding = \Zend\Mime\Mime::ENCODING_BASE64;
		if($fileName === null) throw new \Exception("Param 'filename' can not be null");
		$attachmentPart = new \Zend\Mime\Part();
		$attachmentPart
			->setContent($content)
			->setType($fileType)
			->setDisposition($disposition)
			->setEncoding($encoding)
			->setFileName($fileName)
		;
		$this->_parts[] = $attachmentPart;
		$this->setPartsToBody();
		return $this;
	}

	public function setPartsToBody() {
		$mimeMessage = new \Zend\Mime\Message();
		$mimeMessage->setParts($this->_parts);
		$this->setBody($mimeMessage);
		return $this;
	}
}