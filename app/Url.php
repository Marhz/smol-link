<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    protected $guarded = [];
    protected $appends = ['path'];

    public static function boot()
    {
    	parent::boot();

    	static::creating(function ($url) {
    		$url->makeSlug();
    	});
    }

    public function getRouteKeyName()
    {
    	return 'slug';
    }

    public function getPathAttribute()
    {
    	return $this->attributes['path'] = route('url.show', ['url' => $this->slug]);
    }

    public function save(array $options = [])
    {
    	parent::save($options);
    	$this->visits = 0;
    }

    protected function makeSlug()
    {
    	$base = collect(str_split("azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN1234567890-_"));
    	do {
    	    $slug = '';
    	    for ($i = 0; $i < 7; $i++) {
    	        $slug .= $base->random();
    	    }
    	} while ($this->where('slug', $slug)->exists());
    	$this->slug = $slug;
    }
}
