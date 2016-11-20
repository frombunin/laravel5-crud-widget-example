<?php

namespace App\Http\Services;

use \Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class PageService
{
    protected $pages;

    public static $saveRules = [
        'name' => 'required|min:4|max:256'
    ];

    public function __construct(\App\Page $pages)
    {
        $this->pages = $pages;
    }

    public function save(array $attrs)
    {
        $validator = Validator::make($attrs, self::$saveRules);
    		if ($validator->fails()) {
          return $validator;
        }

        $attrs['slug'] = $this->generateSlug($attrs['name']);

        if ( isset($attrs['id']) && $this->pages->find($attrs['id']) ) {
            return $this->save($attrs);
        }
        return $this->create($attrs);
    }

    public function create($attrs)
    {
      return $this->pages->create($attrs);
    }

    public function update($attrs)
    {
        return $this->pages->update($attrs);
    }

    public function take()
    {
        return $this->pages->orderBy('created_at', -1)->get();
    }

    protected function generateSlug($name)
    {
        return $name.'.html';
    }
}
