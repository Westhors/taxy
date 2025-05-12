<?php

namespace App\Http\Controllers;

use App\Http\Resources\PageResource;
use App\Interfaces\PageRepositoryInterface;
use App\Models\Page;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class PageController extends Controller
{
    use HttpResponses;

    protected $repository;

    public function __construct(PageRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $pages = $this->repository->all();
        return $this->success($pages, 'Pages fetched successfully');
    }

    public function show($slug)
    {
        $page = $this->repository->findBySlug($slug);
        if (!$page) {
            return $this->notFound('Page not found.');
        }
        return $this->success(new PageResource($page));
    }
}
