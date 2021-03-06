<?php

namespace Proxies\__CG__;

use Doctrine\ODM\MongoDB\Persisters\DocumentPersister;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ODM. DO NOT EDIT THIS FILE.
 */
class Post extends \Post implements \Doctrine\ODM\MongoDB\Proxy\Proxy
{
    private $__documentPersister__;
    public $__identifier__;
    public $__isInitialized__ = false;
    public function __construct(DocumentPersister $documentPersister, $identifier)
    {
        $this->__documentPersister__ = $documentPersister;
        $this->__identifier__ = $identifier;
    }
    /** @private */
    public function __load()
    {
        if (!$this->__isInitialized__ && $this->__documentPersister__) {
            $this->__isInitialized__ = true;

            if (method_exists($this, "__wakeup")) {
                // call this after __isInitialized__to avoid infinite recursion
                // but before loading to emulate what ClassMetadata::newInstance()
                // provides.
                $this->__wakeup();
            }

            if ($this->__documentPersister__->load($this->__identifier__, $this) === null) {
                throw \Doctrine\ODM\MongoDB\DocumentNotFoundException::documentNotFound(get_class($this), $this->__identifier__);
            }
            unset($this->__documentPersister__, $this->__identifier__);
        }
    }

    /** @private */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    
    public function update($title, $text, $html)
    {
        $this->__load();
        return parent::update($title, $text, $html);
    }

    public function publish()
    {
        $this->__load();
        return parent::publish();
    }

    public function unpublish()
    {
        $this->__load();
        return parent::unpublish();
    }

    public function setAuthor($author)
    {
        $this->__load();
        return parent::setAuthor($author);
    }

    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return $this->__identifier__;
        }
        $this->__load();
        return parent::getId();
    }

    public function getTitle()
    {
        $this->__load();
        return parent::getTitle();
    }

    public function getSlug()
    {
        $this->__load();
        return parent::getSlug();
    }

    public function getText()
    {
        $this->__load();
        return parent::getText();
    }

    public function getHtml()
    {
        $this->__load();
        return parent::getHtml();
    }

    public function getCreated()
    {
        $this->__load();
        return parent::getCreated();
    }

    public function getUpdated()
    {
        $this->__load();
        return parent::getUpdated();
    }

    public function getPublished()
    {
        $this->__load();
        return parent::getPublished();
    }

    public function isPublished()
    {
        $this->__load();
        return parent::isPublished();
    }

    public function getAuthor()
    {
        $this->__load();
        return parent::getAuthor();
    }

    public function getUpdatedBy()
    {
        $this->__load();
        return parent::getUpdatedBy();
    }

    public function getPublishedBy()
    {
        $this->__load();
        return parent::getPublishedBy();
    }

    public function tag(array $tags)
    {
        $this->__load();
        return parent::tag($tags);
    }

    public function addTags($tags)
    {
        $this->__load();
        return parent::addTags($tags);
    }

    public function addTag($tag)
    {
        $this->__load();
        return parent::addTag($tag);
    }

    public function removeTag($tag)
    {
        $this->__load();
        return parent::removeTag($tag);
    }

    public function getTags()
    {
        $this->__load();
        return parent::getTags();
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'title', 'slug', 'text', 'html', 'created', 'updated', 'published', 'deleted', 'isPublished', 'author', 'updatedBy', 'publishedBy', 'tags');
    }

    public function __clone()
    {
        if (!$this->__isInitialized__ && $this->__documentPersister__) {
            $this->__isInitialized__ = true;
            $class = $this->__documentPersister__->getClassMetadata();
            $original = $this->__documentPersister__->load($this->__identifier__);
            if ($original === null) {
                throw \Doctrine\ODM\MongoDB\MongoDBException::documentNotFound(get_class($this), $this->__identifier__);
            }
            foreach ($class->reflFields AS $field => $reflProperty) {
                $reflProperty->setValue($this, $reflProperty->getValue($original));
            }
            unset($this->__documentPersister__, $this->__identifier__);
        }
        
    }
}