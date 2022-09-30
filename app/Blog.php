<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Blog extends Model
{
    protected $fillable = ['title','post','post_excerpt','slug','user_id','featuredImage','metaDescription','views','jsonData'];

    public function setSlugAttribute($title){
        $this->attributes['slug'] = $this->uniqueSlug($title); // this is ==> this-is
    }
    private function uniqueSlug($title){
        $slug = Str::slug($title,'-');
        $count = Blog::where('slug','LIKE',"{$slug}%")->count();
        $newCount = $count > 0 ? ++$count : '';
        return $newCount > 0 ? "$slug-$newCount" : $slug;
    }
//     public function getCreatedAtAttribute($date)
// {
//     return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m-d');
// }
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse(parent::fromDateTime($value))->format('d-m-Y');
    }
    // protected $dateFormat = 'U';
    public function tag(){
        return $this->belongsToMany('App\Tag','blogtags');
    }
    public function cat(){
        return $this->belongsToMany('App\Category','blogcategories');
    }
    
}
