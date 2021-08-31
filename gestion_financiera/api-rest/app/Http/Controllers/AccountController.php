<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Accounts;
use App\Models\Person;
use App\Models\Transactions;

class AccountController extends Controller
{
    
    public function index()
    {
        //return Account::all();
        //$users = accounts::join('People', 'accounts.id_person', '=', 'People.id_person')->get(['accounts.*', 'People.*']);
        //$orderList = DB::table('Accounts')->join('People', 'accounts.id_person', '=', 'People.id_person')->join('Transactions', 'Transactions.id_account', '=', 'Transactions.id_account')->get();
        $data = accounts::leftJoin('People', 'accounts.id_person', 'People.id_person')
        ->leftJoin('Transactions', 'Transactions.id_account', 'accounts.id_account')
        ->select()
        ->get();
        return $data;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       dd('estas en laravel');
        $data = request()->json()->all();
        //$account->password = Hash:make("admin");
        if(isset($data[0])){
            foreach($data as $obj){
                $this->saveRouteCreate($obj);
            }
        }else{
            $this->saveRouteCreate($data);
        }
        $data=[
            'code'=>200,
            'status'=>'success',
            'campo'=>$request->json()->all()
        ];
        return response()->json($data,$data['code']);
        //return response()->json('ok');
    }

    private function saveRouteCreate($obj){
        
        $peoples = new Person;
        $peoples->cc_person=$obj['people']['cc_person'];
        $peoples->name=$obj['people']['name'];
        $peoples->save();
    
        $id_person=Person::orderBy('created_at', 'desc')->first();
        $account = new accounts;
        $account->noaccount=$obj['accounts']['noaccount'];
        $account->id_person=$id_person['id_person'];
        $account->password = $obj['accounts']['password'];
        $account->save();
        $id_account=accounts::orderBy('created_at', 'desc')->first();
        $transaction = new transactions;
        $transaction->id_account=$id_account['id_account'];
        $transaction->value=$obj['transactions']['value'];
        $transaction->type=$obj['transactions']['type'];
        $transaction->save();
        return true;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //public function show(Account $account)
    public function show($id_account)
    {
       //return Account::find($account->id_account);
       $data = accounts::leftJoin('People', 'accounts.id_person', 'People.id_person')
       ->leftJoin('Transactions', 'Transactions.id_account', 'accounts.id_account')
       ->select()
       ->where('accounts.id_account', $id_account)
       ->get();
       return $data;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $Account->update($request->all());
        return $Account;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
