<?php

/**
 * An extension of Pimple to allow for one Container interface to subsume the other,
 * thus creating the ability to partially instantiate an injection container.
 */
namespace Bismarck;

class Container extends \Pimple\Container
{
    private $parameters = array();


    public function offsetSet( $name, $value )
    {
        $this->parameters[ $name ] = $name;

        parent::offsetSet( $name, $value );
    }


    public function offsetUnset( $name )
    {
        unset( $this->_parameters[ $name ] );

        parent::offsetUnset( $name );
    }


    /**
     * @throws \UnexpectedValueException When the passed in container has values that the existing container has set and $destroy is not true.
     */
    public function subsume( Container $container, $destroy = false )
    {
        foreach ( $container->getParameters() as $parameter )
        {
            if ( $destroy || !isset( $this[ $parameter ] ) )
            {
                $this[ $parameter ] = $container[ $parameter ];

                unset( $container[ $parameter ] );
            }
            else
            {
                $message = 'Existing value passed to Container::subsume() without destroy flag set.';
                throw new \UnexpectedValueException( $message );
            }
        }
    }


    public function getParameters()
    {
        return $this->parameters;
    }
}
