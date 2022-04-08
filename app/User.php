<?php

namespace App;

use App\Member\Address;
use App\Member\Favorite;
use App\Member\Point;
use App\Member\PointHistory;
use App\Member\Tabungan;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    protected $table = 'users';
    protected $guarded = [];
    public $timestamps = false;
}
