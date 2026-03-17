


@extends('common.layout')
@section('title-form', 'Create your account')
@section('content-form')

<form class="space-y-3" method="POST" action="{{ route('common.store') }}">
  @csrf
  <div>
    <label for="name" class="block text-sm font-medium text-gray-700">Your name</label>
    <input type="text" id="name" placeholder="e.g., John Smith" name = "name"
           class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"/>
  </div>

  <div>
    <label for="email" class="block text-sm font-medium text-gray-700">Your email</label>
    <input type="email" id="email" placeholder="e.g., name@company.com" name="email"
           class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"/>
  </div>

  <div>
    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
    <input type="password" id="password" placeholder="••••••••"
           class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"/>
    <p id="passwordError" class="text-sm text-red-500 mt-1 hidden">Password must be at least 8 characters.</p>
  </div>

  <div>
    <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirm Password</label>
    <input type="password" id="confirm_password" placeholder="••••••••" name="password"
           class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"/>
    <p id="confirmError" class="text-sm text-red-500 mt-1 hidden">Passwords do not match.</p>
  </div>

  <input type="hidden" name="role" value="{{ $user_type }}" />
  <!-- <div class="flex items-center justify-between">
    <div class="flex items-center">
      <input id="remember" type="checkbox"
             class="h-4 w-4 text-blue-600 border border-gray-400 rounded focus:ring-blue-500"/>
      <label for="remember" class="ml-2 block text-sm text-gray-900">Remember me</label>
    </div>
    <a href="#" class="text-sm text-blue-600 hover:underline">Forgot password?</a>
  </div> -->

  <button type="submit"
          class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-md transition">
    Signup
  </button>

  <p class="text-sm text-center text-gray-600 mt-4">
    Already have an account?
    <a href="{{ route($user_type . '.login') }}" class="text-blue-600 hover:underline">Sign in</a>
  </p>
</form>
@endsection