@extends('common.layout')
@section('title-form', 'Forgot your password?')
@section('content-form')

<p class="text-sm text-gray-500 mb-6">Type in your registered email in the field below and we will send you a code to reset your password.</p>

    <form class="space-y-3" method='POST' action='{{ route("common.emailverify") }}'>
      @csrf
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Your email</label>
        <input type="email" name="email" id="email" placeholder="eg., name@company.com" required
               class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"/>
      </div>

      <input type="text" value='{{ $user_type }}' name='user_type' class="hidden"/>
      <input type="text" value='R' name='page_code' class="hidden"/>

      <!-- <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input type="password" id="password" placeholder="••••••••"
               class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"/>
      </div> -->

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
        Send Code
      </button>

      <p class="text-sm text-center text-gray-600 mt-4">
        <!-- Don't have an account? -->
        <a href="{{ route( $user_type . '.login') }}" class="text-blue-600 hover:underline">Go back</a>
      </p>
    </form>
@endsection
