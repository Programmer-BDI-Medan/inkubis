<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Laravel\Socialite\Socialite;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use App\Http\Requests\Auth\LoginRequest;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();
        // dd(Auth::user()->role);
        return $this->getRedirectRoute(Auth::user());
    }

    public function google_redirect()
    {
        return Socialite::driver('google')->redirect();
    }
    public function google_callback()
    {
        // try catch kalo error
        if (request()->has('error') || !request()->has('code')) {
        return redirect()->route('login')
            ->with('error', 'Login dibatalkan atau terjadi kesalahan.');
    }

        $googleUser = Socialite::driver('google')->user();
        $user = User::whereEmail($googleUser->getEmail())->first();
        if (!$user) {
            $user = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'password' => bcrypt(str()->random(24))
            ]);
        } 
        Auth::login($user);

        request()->session()->regenerate();

        return $this->getRedirectRoute($user);
    }

    private function getRedirectRoute($user)
        {
            $roleRoutes = [
                'super_admin'=> 'admin.dashboard',
                'admin' => 'admin.dashboard',
                'staff'       => 'staff.dashboard',
                'tenant'      => 'tenant.dashboard',
            ];

            $routeName = $roleRoutes[$user->role] ?? 'dashboard';

            return redirect()->intended(route($routeName, absolute: false));
        }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
