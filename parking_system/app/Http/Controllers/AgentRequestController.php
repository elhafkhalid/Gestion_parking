<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AgentRequest;
use Illuminate\Support\Facades\Auth;

class AgentRequestController extends Controller
{
    public function create(Request $request)
    {
        $step = $request->step ?? 1;
        return view('user.agent-request', compact('step'));
    }

    public function processStep(Request $request)
    {
        $step = $request->step;

        if ($step == 1) {
            $request->validate([
                'phone' => 'required',
                'age' => 'required|integer|min:18',
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
                'identity_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'cv_document' => 'required|file|mimes:pdf,doc,docx|max:5120',
            ]);

            // On stocke temporairement les fichiers
            $identityPath = $request->file('identity_document')
                ->store('temp_agent_documents', 'public');

            $cvPath = $request->file('cv_document')
                ->store('temp_agent_documents', 'public');

            session()->put('agent_step_3', [
                'identity_document' => $identityPath,
                'cv_document' => $cvPath,
            ]);

            return redirect()->route('user.agent.create', ['step' => 4]);
        }

        session()->put("agent_step_$step", $request->except('_token'));

        return redirect()->route('user.agent.create', [
            'step' => $step + 1
        ]);
    }

    public function store()
    {
        $user = Auth::user();

        if ($user->agentRequest) {
            return redirect()
                ->route('user.dashboard')
                ->with('error', 'Vous avez déjà envoyé une demande.');
        }

        $step1 = session('agent_step_1');
        $step2 = session('agent_step_2');
        $step3 = session('agent_step_3');

        if (!$step1 || !$step2 || !$step3) {
            return redirect()
                ->route('user.agent.create')
                ->with('error', 'Processus incomplet.');
        }

        AgentRequest::create([
            'user_id' => $user->id,
            'phone' => $step1['phone'],
            'age' => $step1['age'],
            'experience' => $step2['experience'],
            'availability' => $step2['availability'],
            'motivation' => $step2['motivation'] ?? null,
            'identity_document' => $step3['identity_document'],
            'cv_document' => $step3['cv_document'],
            'status' => 'pending',
        ]);

        session()->forget([
            'agent_step_1',
            'agent_step_2',
            'agent_step_3'
        ]);

        return redirect()
            ->route('user.dashboard')
            ->with('success', 'Votre candidature a été envoyée avec succès.');
    }
}
