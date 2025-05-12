<?php

namespace App\Interfaces;

use App\Models\Page;
use App\Repositories\ICrudRepository;

interface PageRepositoryInterface extends ICrudRepository
{
    public function findBySlug(string $slug): ?Page;
}