<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController as Controller;
use App\Repositories\NewsRepository;

use App\Models\News;

class NewsController extends Controller
{
    /** @var  NewsRepository */
    private $newsRepository;

    public function __construct(NewsRepository $newsRepo)
    {
        $this->newsRepository = $newsRepo;
    }

    public function index()
    {
        $news  = $this->newsRepository->api();
        return $this->sendResponse($news, "success");
    }

    public function show($id)
    {

        $news = $this->newsRepository->with('newsCategory')->findWithoutFail($id);

        if (empty($news)) {

            return $this->sendError('News not found.', 404);

        }

        return $this->sendResponse($news, "success");
    }
}
