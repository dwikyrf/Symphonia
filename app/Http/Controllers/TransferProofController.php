<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Storage;

class TransferProofController extends Controller
{
    public function show(Transaction $transaction, string $stage = null)
    {
        $user = auth()->user();

        // ── Autorisasi ──
        $isOwner = $transaction->user_id === $user->id;
        $isAdmin = $user->role === 'admin';
        abort_unless($isOwner || $isAdmin, 403);

        // ── Pilih file ──
        $file = $stage === 'dp'
            ? $transaction->transfer_proof_dp
            : ($transaction->transfer_proof_full ?: $transaction->transfer_proof_dp);

        abort_unless($file, 404);

        // ── Response inline (atau ->download) ──
        return Storage::disk('private')->response($file);
    }
}
