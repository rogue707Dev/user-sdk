<?php 
namespace Compredict\User\Facades;

use Illuminate\Support\Facades\Facade;
/**
 * Facade for the Comrpedict service
 *
 */
class CompredictFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'compredict_users';
    }
}