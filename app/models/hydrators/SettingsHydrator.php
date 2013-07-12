<?php

namespace Hydrators;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Doctrine\ODM\MongoDB\Hydrator\HydratorInterface;
use Doctrine\ODM\MongoDB\UnitOfWork;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ODM. DO NOT EDIT THIS FILE.
 */
class SettingsHydrator implements HydratorInterface
{
    private $dm;
    private $unitOfWork;
    private $class;

    public function __construct(DocumentManager $dm, UnitOfWork $uow, ClassMetadata $class)
    {
        $this->dm = $dm;
        $this->unitOfWork = $uow;
        $this->class = $class;
    }

    public function hydrate($document, $data, array $hints = array())
    {
        $hydratedData = array();

        /** @Field(type="id") */
        if (isset($data['_id'])) {
            $value = $data['_id'];
            $return = $value instanceof \MongoId ? (string) $value : $value;
            $this->class->reflFields['id']->setValue($document, $return);
            $hydratedData['id'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['blogName'])) {
            $value = $data['blogName'];
            $return = (string) $value;
            $this->class->reflFields['blogName']->setValue($document, $return);
            $hydratedData['blogName'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['blogDescription'])) {
            $value = $data['blogDescription'];
            $return = (string) $value;
            $this->class->reflFields['blogDescription']->setValue($document, $return);
            $hydratedData['blogDescription'] = $return;
        }
        return $hydratedData;
    }
}