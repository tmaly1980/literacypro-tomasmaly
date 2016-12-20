<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Carbon\Carbon;

class Band extends Model
{
    //
    use Sortable;

    public $sortable = ['id','name','start_date','website','still_active'];

    function albums()
    {
    	return $this->hasMany('App\Album');
    }

    // this is a recommended way to declare event handlers
    protected static function boot() {
        parent::boot();

        static::deleting(function($band) { // before delete() method call this
             $band->albums()->delete();
             // do the rest of the cleanup...
        });
    }

    public function setStartDateAttribute($date)
    {
      $this->attributes['start_date']= new Carbon($date);
    }
}
