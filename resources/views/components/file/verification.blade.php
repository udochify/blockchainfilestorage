@props(['file'])

@php
$verification_time = "";
$verification_status = "";
if($file->last_verified_at) {
    $verification_date = new DateTime($file->last_verified_at);
    $verification_time = $verification_date->format('D, M j, Y \a\t g:i:s A');
    $verification_status = $file->verification_status;
}
@endphp

@if($verification_time != "")
    @if($verification_status == "Passed")
    <div class="w-4 h-auto"><img src="img/icons/success-icon.png" /></div>
    @else
    <div class="w-4 h-auto"><img src="img/icons/error-icon.png" /></div>
    @endif
    <p class="text-gray-900 w-fit ml-1"><strong>{{ $verification_status }}</strong> last verification on {{ $verification_time }}</p>
@else
    <div class="w-4 h-auto"><img class="w-full" src="img/icons/minus-4-20.png" /></div>
    <p class="text-gray-900 w-fit ml-1"><strong>Unverified</strong></p>
@endif