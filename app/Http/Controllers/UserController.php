<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Loan;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $users = User::get();

      return response()->json( getAPIFormat( '', $users ) );
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
      $registerUser= $request->except('_token');

      $registerUser['password'] = Hash::make($registerUser['password']);

      try {
          User::insert( $registerUser );
      } catch (\Exception $e) {
          return response()->json( getAPIFormat('Error saving to database', null, $e), 500 );
      }

      return response()->json( getAPIFormat('User successfully created'), 201 );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($userId)
    {
      $user = User::find( $userId );

      if (!$user) {
          return response()->json( getAPIFormat('User not found'), 404);
      }

      return response()->json( getAPIFormat('', $user) );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $userId)
    {
      $user = User::find( $userId );

      if (!$user) {
          return response()->json( getAPIFormat('User not found'), 404);
      }

      $validator = \Validator::make($request->all(), [
          'name' => ['required', 'string', 'max:191'],
          'email' => ['required', 'string', 'email', 'max:191'],
          'password' => ['required', 'string', 'min:6'],
      ]);

      if ($validator->fails())
      {
          return response()->json( getAPIFormat(null, null, $validator->errors()->all()), 400);
      }

      $updateUser= $request->except('_token');

      try {
        $user->update( $updateUser );
      } catch (\Exception $e) {
          return response()->json( getAPIFormat('Error saving to database', null, $e), 500 );
      }

      return response()->json( getAPIFormat('User successfully updated'), 204 );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($userId)
    {
      $user = User::find( $userId );

      if (!$user) {
          return response()->json( getAPIFormat('User not found'), 404);
      }

      $userLoan = Loan::where('user_id', $userId)->first();

      if($userLoan){
          return response()->json( getAPIFormat('User can not delete'), 403 );
      }

      $user->delete();

      return response()->json( getAPIFormat('User is deleted') );
    }
}
