<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoanRequest;
use App\Http\Resources\LoanResource;
use App\Models\Loan;
use App\Models\LoanRepayment;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    /**
     * Display a listing of Loan.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        try {
            $loan  = Loan::with('loanRepayments')->where('user_id', auth()->user()->id)->get();
            return response()->json($loan);
        } catch (\Throwable $th) {
            return response()->json([
                'success'    => false,
                'message'   => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Create a Loan
     *
     * @param  \App\Http\Requests\LoanRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LoanRequest $request) {
        try { 
            $data['loan_amount'] = $request['loan_amount'];
            $data['loan_term'] = $request['loan_term'];
            $data['user_id'] = auth()->user()->id;
            $data['status'] = 1;
            $data['approval_status'] = 0;
            $loan = Loan::create($data);
            $this->createRepayments($loan);
            return new LoanResource($loan);
            
        } catch (\Throwable $th) {
            return response()->json([
                'success'    => false,
                'message'   => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Create repayments (EMI)
     * @param  App\Models\Loan  $loanData
     */
    public function createRepayments($loanData) {
        try {
            $loanAmount = $loanData['loan_amount'];
            $loanTerm = $loanData['loan_term'];
            $totalrepaymentAmount = 0;
            for($i = 1; $i <= $loanTerm; $i++){
                $repaymentAmount = $i == $loanTerm ? ($loanAmount - $totalrepaymentAmount)  : number_format(($loanAmount/ $loanTerm), 2, '.', '');
                $totalrepaymentAmount += $repaymentAmount;
                $data['loan_id'] = $loanData['id'];
                $data['amount'] = $repaymentAmount;
                $data['status'] = 1;
                LoanRepayment::create($data);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success'    => false,
                'message'   => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Admin can change status of Loan
     * @param  \App\Http\Requests\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function changeLoanStatus(Request $request) {
        try {
            // Check only ADMIN can change loan status
            if(auth()->user()->is_admin !== 1){
                return response()->json([
                    'success'    => false,
                    'message'   => 'You are not authorized person to do this action'
                ], 401);
            }
            // Validation Rules
            $valiateUser = Validator::make($request->all(), [
                'loan_id' => 'required|numeric|min:1|exists:loans,id',
                'approval_status' => 'required|numeric|between:0,2',
            ]);

            if($valiateUser->fails()){
                return response()->json([
                    'success'    => false,
                    'message'   => 'Validation error',
                    'errors'    => $valiateUser->errors()
                ], 401);
            }

            Loan::where('id', $request['loan_id'])->update(
                array('approval_status' => $request['approval_status'], 'modified_user_id' => auth()->user()->id)
            );
            return response()->json([
                'success'    => true,
                'message'   => 'Loan status is changed'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success'    => false,
                'message'   => $th->getMessage()
            ], 500);
        }
    }
}
