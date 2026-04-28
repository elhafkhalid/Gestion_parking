<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AgentRequest;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function create(Request $request)
    {
        $step = $request->step ?? 1;
        return view('user.agent-request', compact('step'));
    }

    public function procesStep(Request $request)
    {
        $step = $request->step;

        if ($step == 1) {
            $request->validate([
                'phone' => 'required',
                'age' => 'required|',
            ]);
        }

        if ($step == 2) {
            $request->validate([
                'experience' => 'required',
                'availability' => 'required',
            ]);
        }

        if ($step == 3) {

            $request->validate([
                'cv' => 'required|file|mimes:pdf',
            ]);

            $cv = $request->file('cv')->store('cv', 'public');

            session()->put("$step", [
                'cv' => $cv,
            ]);

            return redirect()->route('user.agent.create', ['step' => 4]);
        }

        session()->put("$step", $request->all());

        return redirect()->route('user.agent.create', [
            'step' => $step + 1
        ]);
    }

    public function store()
    {
        $user = Auth::user();

        if ($user->agentRequest) {
            return redirect()
                ->route('/')
                ->with('error', 'demande deja envoyer');
        }

        $step1 = session('1');
        $step2 = session('2');
        $step3 = session('3');

        if (!$step1 || !$step2 || !$step3) {
            return redirect()
                ->route('user.agent.create')
                ->with('error', 'processus incomplet');
        }

        AgentRequest::create([
            'user_id' => $user->id,
            'phone' => $step1['phone'],
            'age' => $step1['age'],
            'experience' => $step2['experience'],
            'availability' => $step2['availability'],
            'motivation' => $step2['motivation'] ?? null,
            'cv_document' => $step3['cv'],
            'status' => 'pending',
        ]);

        session()->forget([
            '1',
            '2',
            '3',
        ]);

        Auth::logout();

        return redirect()
            ->route('/')
            ->with('success', 'demande envoye ... attend validation');
    }
}
