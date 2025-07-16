<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TokenMetric;
use App\Services\TokenService;
use App\Models\price_histories;
use App\Models\User;


class TokenController extends Controller
{
    protected TokenService $tokenService;

    public function __construct(TokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }
     public function price(Request $request)
    {
        $price = $this->tokenService->getMetric()->price;
        return response()->json([
            'price' => (float) $price,
        ]);
    }

    public function showTokenInfo()
    {
        $token = TokenMetric::firstOrCreate(
            ['symbol'      => 'MPMO'],
            ['max_supply'  => 1000000,   // adjust to your real cap
             'total_supply'=> 0]   // initial circulating supply
        );
        return view('token.info', compact('token'));
    }

    public function index()
    {

        $metric      = $this->tokenService->getMetric();
        $user        = auth()->user();
        $trxBalance  = $user->trx_balance;    // make sure this column exists on users
        $mpmoBalance = $user->mpmo_balance;   // …and this one, too

        $priceHistory = $metric->priceHistories()->take(50)->get();

        return view('token.index', compact(
            'metric',
            'trxBalance',
            'mpmoBalance',
            'priceHistory'
        ));
    }

    public function convert(Request $request, TokenService $svc)
    {
        $user     = $request->user();
        $maxTrx   = $user->trx_balance;

        $data = $request->validate([
            'trx_amount' => [
                'required',
                'numeric',
                'gt:0',                       // must be > 0
                'max:'.$maxTrx,               // not more than they have
            ],
        ], [
            'trx_amount.max' => "You only have {$maxTrx} TRX available.",
        ]);

        $this->tokenService->convert($user, (float) $data['trx_amount']);
        $user        = auth()->user();
        $trxBalance  = $user->trx_balance;    // make sure this column exists on users
        $mpmoBalance = $user->mpmox_balance; 

        return redirect()
            ->route('token.index')
            ->with(['trxBalance','mpmoBalance'])
            ->with('success', 'TRX successfully converted to MPMO');

        $data = $request->validate(['trx_amount'=>'required|numeric|max:'.auth()->user()->trx_balance]);
        $svc->convert(auth()->user(), $data['trx_amount']);
        $user = auth()->user()->refresh();
        return response()->json(['message'=>'Converted!',
                                'trx_balance'=>$user->trx_balance,
                                'mpmo_balance'=>$user->mpmo_balance]);
    }

    public function redeem(Request $request)
    {
        $user   = $request->user();
        $max    = $user->mpmo_balance;

        $data = $request->validate([
            'mpmo_amount' => ['required','numeric','gt:0',"max:{$max}"],
        ], [
            'mpmo_amount.max' => "You only have {$max} MPMO.",
        ]);

        $this->tokenService->redeem($user, (float)$data['mpmo_amount']);

        return back()->with('success', 'Redeemed to TRX.');
    }

    /**
     * Airdrop tokens from treasury to users.
     * Expects JSON: {"userId":amount, ...}
     */
     public function airdrop(Request $request)
    {
         $data = $request->validate([
            'user_ids'   => ['required','array'],
            'user_ids.*' => ['required','integer','exists:users,userid'],
            'amounts'    => ['required','array'],
            'amounts.*'  => ['required','numeric','gt:0'],
        ]);

        $allocations = array_combine($data['user_ids'], $data['amounts']);
        $this->tokenService->airdrop($allocations);

        return back()->with('success', 'Airdrop completed.');
    }

    /**
     * Distribute staking rewards from treasury.
     * Expects JSON: {"userId":amount, ...}
     */
    public function distributeStakingRewards(Request $request)
    {
        $data = $request->validate([
            'rewards' => ['required','json'],
        ]);

        $rewards = json_decode($data['rewards'], true);
        $this->tokenService->distributeStakingRewards($rewards);
        return back()->with('success', 'Staking rewards distributed.');
    }

    /**
     * Incentivize liquidity provision with treasury tokens.
     * Expects pool_id and JSON allocations: {"userId":amount, ...}
     */
    public function incentivizeLiquidity(Request $request)
    {
        $data = $request->validate([
            'pool_id'     => ['required','integer'],
            'allocations' => ['required','json'],
        ]);

        $allocations = json_decode($data['allocations'], true);
        $this->tokenService->incentivizeLiquidity((int) $data['pool_id'], $allocations);
        return back()->with('success', 'Liquidity incentives allocated.');
    }
    public function procedures()
    {
        $users = User::all();
        return view('token.procedures', compact('users'));
    }

}
