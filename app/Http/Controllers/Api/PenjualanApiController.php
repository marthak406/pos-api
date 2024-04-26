<?php

namespace App\Http\Controllers\Api;

use App\Models\PenjualanApi;
use App\Models\PenjualanApiDetail;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;

class PenjualanApiController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //get all posts
        $posts = PenjualanApi::latest()->paginate(5);

        //return collection of posts as a resource
        return new PostResource(true, 'List Data Penjualan', $posts);
    }
}
