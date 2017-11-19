<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTransactionRequest;
use App\Models\DictionaryType;
use App\Models\Transaction;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use App\Services\Contracts\DictionaryServiceInterface;
use Illuminate\Http\Request;
use Auth;

class TransactionController extends Controller
{
    protected $dictionaries;
    protected $transactionRepository;

    public function __construct(
        DictionaryServiceInterface $dictionaries,
        TransactionRepositoryInterface $transactionRepository
    ) {
        $this->dictionaries          = $dictionaries;
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $transactions = $this->transactionRepository
            ->getByUser()
            ->paginate(50);

        return view('transactions.list', compact('transactions'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $transactionTypes    = $this->dictionaries->getByType(DictionaryType::TRANSACTION_TYPES);
        $userExpensesByMonth = $this->transactionRepository->getUserExpensesByPeriod()['total'];
        $lastTransaction     = $this->transactionRepository->getLastTransaction();

        return view('transactions.create', compact('transactionTypes', 'userExpensesByMonth', 'lastTransaction'));
    }

    /**
     * @param CreateTransactionRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(CreateTransactionRequest $request)
    {
        $result = ['status' => false];
        $data   = $request->only(['type_dictionary_id', 'date', 'amount', 'description']);

        $validator = $this->transactionRepository->validate($data);

        if (!$validator->fails()) {
            $this->transactionRepository->create($data);

            $lastTransaction = $this->transactionRepository->getLastTransaction();

            $result = [
                'status'     => true,
                'total'      => $this->transactionRepository->getUserExpensesByPeriod()['total'],
                'lastAmount' => $lastTransaction->amount,
                'lastType'   => $lastTransaction->getType(),
                'lastDate'   => date('d.m.Y', strtotime($lastTransaction->date)),
            ];
        }

        if ($request->ajax()) {
            return response()->json($result);
        } else {
            return redirect()->back();
        }
    }

    /**
     * @param Transaction $transaction
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Transaction $transaction, Request $request)
    {
        $result = ['status' => false];

        if ($transaction && $transaction->user_id == Auth::user()->id) {
            if ($this->transactionRepository->delete($transaction->id)) {
                $result['status'] = true;
            }
        }

        if ($request->ajax()) {
            return response()->json($result);
        } else {
            return redirect()->back();
        }
    }

}
