@extends('common.layout')
@section('title-form', 'Reset your password')
@section('content-form')
    
    <form class="space-y-3" method="POST" action="{{ route('common.savePassword') }}">
      @csrf
      <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input type="password" id="password" placeholder="••••••••" required
               class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"/>
        <p id="passwordError" class="text-sm text-red-500 mt-1 hidden">Password must be at least 8 characters.</p>
      </div>

      <div>
        <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirm Password</label>
        <input type="password" id="confirm_password" placeholder="••••••••" name="confirm_psw" required
               class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"/>
        <p id="confirmError" class="text-sm text-red-500 mt-1 hidden">Passwords do not match.</p>
      </div>
      
      <input type="text" name="email" id="email" value="{{ $email }}" class="hidden">
      <input type="text" name="user_type" id="user_type" value="{{ $user_type }}" class="hidden">

      <!-- <div class="flex items-center justify-between">
        <div class="flex items-center">
          <input id="remember" type="checkbox"
                 class="h-4 w-4 text-blue-600 border border-gray-400 rounded focus:ring-blue-500"/>
          <label for="remember" class="ml-2 block text-sm text-gray-900">Remember me</label>
        </div>
        <a href="#" class="text-sm text-blue-600 hover:underline">Forgot password?</a>
      </div> -->

      <button id = "submitBtn" type="submit"
              class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-md transition">
        Update Password
      </button>

      <p class="text-sm text-center text-gray-600 mt-4">
        <!-- Don't have an account? -->
        <a href="{{ route( $user_type . '.login') }}" class="text-blue-600 hover:underline">Go back</a>
      </p>
    </form>
@endsection
