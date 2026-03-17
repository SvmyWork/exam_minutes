@extends('common.layout')
@section('title-form', 'Sign in to your account')
@section('content-form')

    <form class="space-y-5" method="POST" action="{{ route('common.authenticate') }}">
      @csrf
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Your email</label>
        <input type="email" name="email" id="email" placeholder="e.g., name@company.com" required
               class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"/>
      </div>

      <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input type="password" name = "password" id="password" placeholder="••••••••" required
               class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"/>
      </div>

      <input type="hidden" id="user_type" name="user_type" value="{{ $user_type }}">

      <div class="flex items-center justify-between">
        <div class="flex items-center">
          <input id="remember" type="checkbox"
                 class="h-4 w-4 text-blue-600 border border-gray-400 rounded focus:ring-blue-500"/>
          <label for="remember" class="ml-2 block text-sm text-gray-900">Remember me</label>
        </div>
        <a href="{{ route( $user_type . '.forgot') }}" class="text-sm text-blue-600 hover:underline">Forgot password?</a>
      </div>

      <button type="submit"
              class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-md transition">
        Log in to your account
      </button>

      <p class="text-sm text-center text-gray-600 mt-4">
        Don't have an account?
        <a href="{{ route( $user_type . '.register') }}" class="text-blue-600 hover:underline">Sign up</a>
      </p>
    </form>
@endsection
