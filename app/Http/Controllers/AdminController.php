<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;

class AdminController extends Controller {
    public function index(){
        $users = User::orderBy('created_at','desc')->paginate(20);
        $transactions = Transaction::orderBy('created_at','desc')->paginate(50);
        return view('admin.index', compact('users','transactions'));
    }
}
