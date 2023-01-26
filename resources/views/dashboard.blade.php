<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Blockchain') }}
            </h2>
            @if(auth()->user()->address)
            <p>&nbsp(your address: {{ auth()->user()->address }})</p>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col bg-white overflow-hidden shadow-sm sm:rounded-lg p-5">
                @if(!auth()->user()->address)
                <form id="blockchain-reg" action="{{ route('blockchain.register') }}" method="POST">
                    @csrf
                    <x-input-label for="email" :value="__('Email')" />
                    <div class="flex flex-row left-0 align-middle">
                        <div class="w-1/2 my-auto">
                            <x-text-input class="block mt-1 w-full" type="email" name="email" :value="auth()->user()->email" required autofocus />
                            <x-text-input class="hidden" type="text" name="name" :value="auth()->user()->name" />
                        </div>
                        <div class="ml-3 my-auto">
                            <x-primary-button>
                                {{ __('Register') }}
                            </x-primary-button>
                        </div>
                    </div>
                    <x-session-status class="text-red-600" :status="session('error')" />
                </form>
                <x-session-status class="text-gray-600" :status="__('Register on the blockchain')" />
                @else
                <form id="file-form" method="POST" action="{{ route('files.upload') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="flex flex-col">
                        <div class="relative flex flex-row items-center">
                            <div class="w-3/4">
                                <x-file.input id="file-input" type="file" name="file" class="form-control block m-0 p-0 w-full" accept=".txt,.pdf,.csv,.xlx,.xls,.xlsx,.doc,.docx,.html,.css,.js,.jpg,.jpeg,.png,.gif,.mp4,.avi,.3gp,.webm,.wav,.ogg,.mp3" required autofocus />
                            </div>
                            <div class="flex flex-col items-end mt-0 w-fit ml-2">
                                <x-file.button id="file-button">
                                    {{ __('Upload') }}
                                </x-file.button>
                            </div>
                            <div id="loading-gif-upload" class="loading-gif w-11 ml-2 my-[-5px]">
                
                            </div>
                        </div>
                        <div class="flex flex-row items-center w-1/2 mt-2">
                            <x-label for="key" :value="__('Private Key')" class="w-fit" />
                            <x-file.input id="file-key" type="password" name="key" class="form-control block h-4 m-0 ml-2 p-0 px-1 grow" required autofocus />
                        </div>
                    </div>
                </form>
                @endif
                <x-session-status class="text-green-600" :status="session('success')" />
                <div id="ajax-status">
                    
                </div>
                <div id="file-panel" class="mt-2">
                    @foreach($files as $file)
                    <x-file.item :file="$file" class="hover:bg-gray-200 relative w-full" />
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
