<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\JobCategoryDataTable;
use App\Http\Controllers\AppBaseController;
use App\Repositories\JobCategoryRepository;

use App\Models\JobCategory;
use Flash;

class JobCategoryController extends AppBaseController
{
    /** @var  JobCategoryRepository */
    private $jobCategoryRepository;
    
    public function __construct(JobCategoryRepository $JobCategoryRepo)
    {
        $this->jobCategoryRepository = $JobCategoryRepo;
    }

    /**
     * Display a listing of the JobCategory.
     *
     * @param JobCategoryDataTable $jobCategoryDataTable
     * @return Response
     */
    public function index(JobCategoryDataTable $jobCategoryDataTable)
    {
        return $jobCategoryDataTable->render('job_categories.index');
    }

    /**
     * Show the form for creating a new JobCategory.
     *
     * @return Response
     */
    public function create()
    {
        return view('job_categories.create');
    }

    /**
     * Store a newly created JobCategory in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $req = $request->all();
 
        $jobCategory = $this->jobCategoryRepository->create($req);
 
        Flash::success('Job Category saved successfully.');
 
        return redirect(route('job_categories.index'));
    }

    /**
     * Show the form for editing the specified JobCategory.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $jobCategory = $this->jobCategoryRepository->findWithoutFail($id);
 
        if (empty($jobCategory)) {
            Flash::error('Job Category not found');
 
            return redirect(route('job_categories.index'));
        }
 
        return view('job_categories.edit')->with('jobCategory', $jobCategory);
    }

    /**
     * Update the specified JobCategory in storage.
     *
     * @param  int              $id
     * @param Request $request
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        $jobCategory = $this->jobCategoryRepository->findWithoutFail($id);
 
        if (empty($jobCategory)) {
            Flash::error('Job Category not found');
 
            return redirect(route('job_categories.index'));
        }
 
        $jobCategory = $this->jobCategoryRepository->update($request->all(), $id);
 
        Flash::success('Job Category updated successfully.');
 
        return redirect(route('job_categories.index'));
    }

    /**
     * Remove the specified JobCategory from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $jobCategory = $this->jobCategoryRepository->findWithoutFail($id);
 
        if (empty($jobCategory)) {
            Flash::error('Job Category not found');
 
            return redirect(route('job_categories.index'));
        }
 
        $this->jobCategoryRepository->delete($id);
 
        Flash::success('Job Category deleted successfully.');
 
        return redirect(route('job_categories.index'));
    }
}
