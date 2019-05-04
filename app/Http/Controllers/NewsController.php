<?php

namespace App\Http\Controllers;

use App\DataTables\NewsDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateNewsRequest;
use App\Http\Requests\UpdateNewsRequest;
use App\Repositories\NewsRepository;
use App\Models\NewsCategory;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class NewsController extends AppBaseController
{
    /** @var  NewsRepository */
    private $newsRepository;

    public function __construct(NewsRepository $newsRepo)
    {
        $this->newsRepository = $newsRepo;
    }

    /**
     * Display a listing of the News.
     *
     * @param NewsDataTable $newsDataTable
     * @return Response
     */
    public function index(NewsDataTable $newsDataTable)
    {
        return $newsDataTable->render('news.index');
    }

    /**
     * Show the form for creating a new News.
     *
     * @return Response
     */
    public function create()
    {
        return view('news.create')->with('news_categories', NewsCategory::pluck('name', 'id'));
    }

    /**
     * Store a newly created News in storage.
     *
     * @param CreateNewsRequest $request
     *
     * @return Response
     */
    public function store(CreateNewsRequest $request)
    {
        $input = $request->all();

        $input['slug'] = str_slug($input['title']);
        $input['photo'] =  base64ToPng($input['photo'], $input['slug'], 'news');

        $news = $this->newsRepository->create($input);

        Flash::success('News saved successfully.');

        return redirect(route('news.index'));
    }

    /**
     * Display the specified News.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $news = $this->newsRepository->findWithoutFail($id);

        if (empty($news)) {
            Flash::error('News not found');

            return redirect(route('news.index'));
        }

        return view('news.show')->with('news', $news);
    }

    /**
     * Show the form for editing the specified News.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $news = $this->newsRepository->findWithoutFail($id);

        if (empty($news)) {
            Flash::error('News not found');

            return redirect(route('news.index'));
        }

        return view('news.edit')->with('news', $news)->with('news_categories', NewsCategory::pluck('name', 'id'));
    }

    /**
     * Update the specified News in storage.
     *
     * @param  int              $id
     * @param UpdateNewsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateNewsRequest $request)
    {
        $news = $this->newsRepository->findWithoutFail($id);

        if (empty($news)) {
            Flash::error('News not found');

            return redirect(route('news.index'));
        }



        $filename = storage_path('app'. DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR .'news') . DIRECTORY_SEPARATOR . $news->slug . '.png';

        if (file_exists($filename)) {
            unlink($filename);
        }


        $input = $request->all();
        $input['photo'] =  base64ToPng($input['photo'], $news->slug, 'news');
        $news = $this->newsRepository->update($input, $id);

        Flash::success('News updated successfully.');

        return redirect(route('news.index'));
    }

    /**
     * Remove the specified News from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $news = $this->newsRepository->findWithoutFail($id);

        if (empty($news)) {
            Flash::error('News not found');

            return redirect(route('news.index'));
        }

        $this->newsRepository->delete($id);

        $filename = storage_path('app'. DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR .'news') . DIRECTORY_SEPARATOR . $news->slug . '.png';

        if (file_exists($filename)) {
            unlink($filename);
        }

        Flash::success('News deleted successfully.');

        return redirect(route('news.index'));
    }
}
