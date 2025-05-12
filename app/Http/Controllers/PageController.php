<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class PageController extends Controller
{
    use HttpResponses;

    public function show($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();
        return $this->success([
            'title' => $page->title,
            'content' => $page->content,
        ]);
    }
}
