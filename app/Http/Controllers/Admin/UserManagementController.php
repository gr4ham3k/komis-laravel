<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ban;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()
            ->withCount('listings')
            ->when($request->filled('q'), function ($q) use ($request) {
                $search = trim((string) $request->input('q'));
                $q->where(function ($subQ) use ($search) {
                    $subQ->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
            })
            ->when($request->input('role') === 'admin', fn ($q) => $q->where('is_admin', true))
            ->when($request->input('role') === 'user', fn ($q) => $q->where('is_admin', false))
            ->when($request->input('status') === 'banned', fn ($q) => $q->where('is_banned', true))
            ->when($request->input('status') === 'active', fn ($q) => $q->where('is_banned', false));

        $users = $query
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function updateBanStatus(Request $request, User $user)
    {
        $validated = $request->validate([
            'is_banned' => ['required', 'boolean'],
        ]);

        if (auth()->id() === $user->id && (bool) $validated['is_banned']) {
            return back()->with('error', 'Nie mozesz zbanowac wlasnego konta.');
        }

        $user->update([
            'is_banned' => (bool) $validated['is_banned'],
        ]);

        if ((bool) $validated['is_banned']) {
            Ban::create([
                'user_id' => $user->id,
                'banned_by' => auth()->id(),
                'reason' => 'Zmieniono status bana w panelu admina',
                'banned_at' => now(),
            ]);
        } else {
            Ban::query()
                ->where('user_id', $user->id)
                ->whereNull('lifted_at')
                ->update(['lifted_at' => now()]);
        }

        return back()->with(
            'success',
            $validated['is_banned'] ? 'Uzytkownik zostal zbanowany.' : 'Ban zostal zdjety.'
        );
    }

    public function updateRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'is_admin' => ['required', 'boolean'],
        ]);

        if (auth()->id() === $user->id && ! (bool) $validated['is_admin']) {
            return back()->with('error', 'Nie mozesz odebrac sobie roli admina.');
        }

        $user->update([
            'is_admin' => (bool) $validated['is_admin'],
        ]);

        return back()->with(
            'success',
            $validated['is_admin'] ? 'Nadano role admina.' : 'Rola admina zostala odebrana.'
        );
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Nie mozesz usunac wlasnego konta.');
        }

        if ((bool) $user->is_admin) {
            return back()->with('error', 'Najpierw odbierz role admina, potem usun konto.');
        }

        $user->delete();

        return back()->with('success', 'Konto uzytkownika zostalo usuniete.');
    }
}
