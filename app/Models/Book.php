<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['title', 'author', 'isbn', 'total_copies', 'available_copies'])]
class Book extends Model
{
    
}
