<?php

namespace App\Http\Controllers;

use App\Http\Requests\Report\ReportRequest;
use App\Http\Requests\Report\UserDashboardReportRequest;
use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        return $this->reportService->list($request);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param ReportRequest $reportRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(ReportRequest $reportRequest)
    {
        return $this->reportService->create($reportRequest);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ReportRequest $reportRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ReportRequest $reportRequest)
    {
        return $this->reportService->update($reportRequest);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete(Request $request)
    {
        return $this->reportService->delete($request);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function adminDashboard()
    {
        return $this->reportService->adminDashboard();
    }

    /**
     * @param UserDashboardReportRequest $userDashboardReportRequest
     * @return mixed
     */
    public function userDashboard(UserDashboardReportRequest $userDashboardReportRequest)
    {
        return $this->reportService->userDashboard($userDashboardReportRequest);
    }
}
