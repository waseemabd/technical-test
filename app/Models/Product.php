<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['title','price', 'desc', 'image', 'user_id'];

    public  const create_update_rules = [
        'title' => 'required',
        'price' => 'required|numeric',
        'user_id' => 'required'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function carts(){
        return $this->belongsToMany(Cart::class,'cart_product')->withPivot('qty')->withTimestamps();
    }

}
