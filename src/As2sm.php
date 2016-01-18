<?php
namespace NiisanTokyo\As2sm;

use React\Promise\Deferred;
use React\Promise\PromiseInterface;
use React\Promise\ExtendedPromiseInterface;

/**
 * The class for an asyncronous process to syncronous manner
 * 
 * This class is a set of functions for writing asyncronous process
 * in syncronous manner by using Generator in PHP 5.5~. 
 */
class As2sm
{
    
    public static function wrap($function)
    {
        $generator = $function();
        
        if ($generator instanceof \Generator) {
            $value = $generator->current();
            return self::execute($generator, $value);
        }
        
        throw new NotGeneratorException('You give not Generator');
        
    }
    
    
    private static function execute($generator, $value)
    {
        if ($value instanceof Deferred) {
            $promise = $value->promise();
        } else {
            $promise = $value;
        }
        
        if ($promise instanceof PromiseInterface) {
            return self::core($generator, $promise);
        }
    }
    
    
    private static function core($generator, $promise)
    {
        return $promise->then(function($res) use ($generator){
            self::execute($generator, $generator->send($res));
        });
        
    }
}
