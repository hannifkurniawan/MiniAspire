<?php

namespace App\Http\Controllers;

use App\Repayment;
use App\Loan;
use Illuminate\Http\Request;

class RepaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $repayment= $request->except('_token');

      $validator = \Validator::make($request->all(), [

          'loan_id' => 'required',
          'amount' => 'required',
          'payment_date' => 'required',
      ]);

      if ($validator->fails())
      {
          return response()->json( getAPIFormat(null, null, $validator->errors()->all()), 400);
      }

      $loan = Loan::find( $request -> loan_id );

      if (!$loan) {
        return response()->json( getAPIFormat('Your loan is not found '), 404 );
      }

      $settledLoan = Loan::where('id', $request -> loan_id)->where('is_settled', 1)->first();


      if($settledLoan){
          return response()->json( getAPIFormat('Repayment can not save.'), 403 );
      }

      try {
          Repayment::insert( $repayment );
          $totalRepayment = Repayment::where('loan_id', $request -> loan_id)->sum('amount');

          $total_interest = $loan -> amount * $loan -> interest_rate * $loan -> duration / 100;

          $totalLoan = $loan -> amount + $loan -> arrangement_fee + $total_interest;

          if ($totalRepayment >= $totalLoan ) {
            $this -> updateSettlement( $loan -> id );
          }

      } catch (\Exception $e) {
          dd($e);
          return response()->json( getAPIFormat('Error saving to database', null, $e), 500 );
      }

      return response()->json( getAPIFormat('Repayment successfully created'), 201 );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    private function updateSettlement($loanId)
    {
      $loan = Loan::find( $loanId );

      if (!$loan) {
          return response()->json( getAPIFormat('Loan is not found'), 404);
      }

      try {
        $loan -> is_settled = 1;
        $loan -> save();

      } catch (\Exception $e) {
          return response()->json( getAPIFormat('Error saving to database', null, $e), 500 );
      }

      return response()->json( getAPIFormat('Loan successfully settled'), 201 );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Repayment  $repayment
     * @return \Illuminate\Http\Response
     */
    public function show(Repayment $repayment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Repayment  $repayment
     * @return \Illuminate\Http\Response
     */
    public function edit(Repayment $repayment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Repayment  $repayment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Repayment $repayment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Repayment  $repayment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Repayment $repayment)
    {
        //
    }
}
