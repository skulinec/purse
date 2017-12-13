<?php

namespace App\Http\Controllers;

use App\Criteria\TransactionReportsCriteria;
use App\Repositories\Contracts\FamilyRepositoryInterface;
use App\Repositories\FamilyRepository;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use App\Repositories\TransactionRepository;
use Illuminate\Http\Request;
use Auth;

class ReportController extends Controller
{
    /** @var TransactionRepository */
    protected $transactionRepository;
    /** @var FamilyRepository */
    protected $familyRepository;

    public function __construct(
        TransactionRepositoryInterface $transactionRepository,
        FamilyRepositoryInterface $familyRepository
    ) {
        $this->transactionRepository = $transactionRepository;
        $this->familyRepository      = $familyRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $familyMembers = $this->familyRepository->getAllMembers(Auth::user()->family_id);

        $transactionsByDays  = $this->transactionRepository->getGroupedBy($request,
            TransactionReportsCriteria::TYPE_BY_DAYS);
        $transactionsByTypes = $this->transactionRepository->getGroupedBy($request,
            TransactionReportsCriteria::TYPE_BY_TYPES);
        $transactionsByUsers = $this->transactionRepository->getGroupedBy($request,
            TransactionReportsCriteria::TYPE_BY_USERS);

        return view('reports.index', [
            'familyMembers'       => $familyMembers,
            'transactionsByDays'  => json_encode($transactionsByDays, JSON_FORCE_OBJECT),
            'transactionsByTypes' => json_encode($transactionsByTypes, JSON_FORCE_OBJECT),
            'transactionsByUsers' => json_encode($transactionsByUsers, JSON_FORCE_OBJECT)
        ]);
    }

}
