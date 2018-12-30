<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Loan;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $loan = Loan::get();

      return response()->json( getAPIFormat( '', $loan ), 200 );
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
      $loan= $request->except('_token');

      $validator = \Validator::make($request->all(), [

          'user_id' => 'required',
          'amount' => ['required', 'integer', 'min:1000000'],
          'duration' => ['required', 'integer', 'min:12'],
          'repayment_frequency' => 'required',
          'interest_rate'  => ['required', 'min:0.01'],
          'arrangement_fee'  => 'required',
      ]);



      if ($validator->fails())
      {
          return response()->json( getAPIFormat(null, null, $validator->errors()->all()), 400);
      }

      $notSettledLoan = Loan::where('user_id', $request -> user_id)->where('is_settled', 0)->first();

      if($notSettledLoan){
          return response()->json( getAPIFormat('You have unsettled loan '), 403 );
      }

      try {
          $total_interest = $request -> amount * $request -> interest_rate * $request -> duration / 100;

          $totalLoan = $request -> amount + $request -> arrangement_fee + $total_interest;

          $fix_installment = $totalLoan / $request -> duration;

          $loan['fix_installment'] = $fix_installment;

          Loan::insert( $loan );
      } catch (\Exception $e) {
          return response()->json( getAPIFormat('Error saving to database', null, $e), 500 );
      }

      return response()->json( getAPIFormat('Loan successfully created'), 201 );


    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function show( $loanId)
    {
      $loan = Loan::find( $loanId );

      if (!$loanId) {
          return response()->json( getAPIFormat('Loan not found'), 404);
      }

      return response()->json( getAPIFormat('', $loan) );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function edit(Loan $loan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $loanId)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Loan $loan)
    {
        //
    }
}
