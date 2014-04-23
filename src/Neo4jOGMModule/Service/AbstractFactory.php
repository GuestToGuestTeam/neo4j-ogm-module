<?php

namespace Neo4jOGMModule\Service;

use RuntimeException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @link    http://opensoftstudio.com/
 * @author  OpenSoft <opensoft@opensoftstudio.com>
 */
abstract class AbstractFactory implements FactoryInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var \Zend\Stdlib\AbstractOptions
     */
    protected $options;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param  ServiceLocatorInterface      $sl
     * @param  string                       $key
     * @param  null|string                  $name
     * @return \Zend\Stdlib\AbstractOptions
     * @throws \RuntimeException
     */
    public function getOptions(ServiceLocatorInterface $sl, $key, $name = null)
    {
        if ($name === null) {
            $name = $this->getName();
        }

        $options = $sl->get('Configuration');
        $options = $options['neo4j'];
        $options = isset($options[$key][$name]) ? $options[$key][$name] : null;

        if (null === $options) {
            throw new RuntimeException(
                sprintf(
                    'Options with name "%s" could not be found in "neo4j.%s".',
                    $name,
                    $key
                )
            );
        }

        $optionsClass = $this->getOptionsClass();

        return new $optionsClass($options);
    }

    /**
     * @abstract
     * @return string
     */
    abstract public function getOptionsClass();
}
