<?php

namespace Compredict\User\Auth\Models;

use Illuminate\Support\Facades\DB;
use \App;

trait BelongsToManyTrait
{
    public function belongsToMany($related, $table = null, $foreignPivotKey = null, $relatedPivotKey = null,
        $parentKey = null, $relatedKey = null, $relation = null) {
        // If no relationship name was passed, we will pull backtraces to get the
        // name of the calling function. We will use that function name as the
        // title of this relation since that is a great convention to apply.
        if (is_null($relation)) {
            $relation = $this->guessBelongsToManyRelation();
        }

        // First, we'll need to determine the foreign key and "other key" for the
        // relationship. Once we have determined the keys we'll make the query
        // instances as well as the relationship instances we need for this.
        $instance = $this->newRelatedInstance($related);

        $foreignPivotKey = $foreignPivotKey ?: $this->getForeignKey();

        $relatedPivotKey = $relatedPivotKey ?: $instance->getForeignKey();

        // If no table name was provided, we can guess it by concatenating the two
        // models using underscores in alphabetical order. The two model names
        // are transformed to snake case from their default CamelCase also.
        if (is_null($table)) {
            $table = $this->joiningTable($related, $instance);
        }

        $relatedPivotRecords = DB::table($table)->select($relatedPivotKey)->where($foreignPivotKey, $this->id)->get();
        return $this->processResponse(App::make('CP_User')::getUsersById($relatedPivotRecords->keyBy($relatedPivotKey)->keys()->toArray()));
    }

    private function processResponse($response)
    {
        if ($response === false) {
            return false;
        }

        $users = [];
        foreach ($response as $user) {
            $obj = new \App\User();
            $obj->user = $user;
            array_push($users, $obj);
        }
        return $users;
    }
}
