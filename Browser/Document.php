<?php

namespace Dark\TranslationBundle\Browser;

/**
 * Documentation file object
 *
 * @author Evgeniy Guseletov <d46k16@gmail.com>
 */
class Document {

    private $name;
    private $createdAt;
    private $isTranslated = false;
    private $isDir;
    private $isChanged;

    public function __toString()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setCreatedAt($time)
    {
        $this->createdAt = $time;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function isTranslated()
    {
        return $this->isTranslated;
    }

    public function setIsTranslated($value)
    {
        $this->isTranslated = $value;
    }

    public function isDir()
    {
        return $this->isDir;
    }

    public function setIsDir($value)
    {
        $this->isDir = $value;
    }

    public function isChanged()
    {
        return $this->isChanged;
    }

    public function setIsChanged($value)
    {
        $this->isChanged = $value;
    }
}