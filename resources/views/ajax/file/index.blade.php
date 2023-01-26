
<x-file id="sidebar" class="relative w-80 h-full p-3 border border-gray-300 rounded-br-md bg-gray-50 z-20">
    <div id="file-form-div" class="absolute w-[92%] top-[125px]">
        <form id="file-form" method="POST" action="{{ route('files.upload') }}" enctype="multipart/form-data">
            @csrf
            <div class="relative flex flex-row items-center">
                <div class="w-3/4">
                    <x-file.input id="file-input" type="file" name="file" class="form-control block m-0 p-0 w-full" accept=".txt,.pdf,.csv,.xlx,.xls,.xlsx,.doc,.docx,.html,.css,.js,.jpg,.jpeg,.png,.gif,.mp4,.avi,.3gp,.webm,.wav,.ogg,.mp3" required autofocus />
                </div>
                <div class="flex flex-col items-end mt-0 w-1/3">
                    <x-file.button id="file-button">
                        {{ __('Upload') }}
                    </x-file.button>
                </div>
            </div>
            <div class="flex flex-row items-center mt-2">
                <x-label for="key" :value="__('Private Key')" class="w-fit" />
                <x-file.input id="file-key" type="password" name="key" class="form-control block h-4 m-0 ml-2 p-0 px-1 grow" required autofocus />
            </div>
        </form>
    </div>
    <x-file id="files-container" class="absolute w-[96%] border-solid border-y-[1px] border-gray-200 overflow-y-scroll bottom-2 top-48 right-[1px]">
        @foreach($files as $file)
        <x-file.item :file="$file" class="hover:bg-gray-200 relative w-full" />
        @endforeach
    </x-file>
    <!-- <div id="file-form-div" class="absolute w-[92%] bottom-2">
        <form id="file-form" method="POST" action="{{ route('files.upload') }}" enctype="multipart/form-data">
            @csrf
            <div class="relative flex flex-row items-center">
                <div class="w-3/4">
                    <x-file.input id="file-input" type="file" name="file" class="form-control block m-0 p-0 w-full" accept=".txt,.pdf,.csv,.xlx,.xls,.xlsx,.doc,.docx,.html,.css,.js,.jpg,.jpeg,.png,.gif,.mp4,.avi,.3gp,.webm,.wav,.ogg,.mp3" required autofocus />
                </div>
                <div class="flex flex-col items-end mt-0 w-1/3">
                    <x-file.button id="file-button">
                        {{ __('Upload') }}
                    </x-file.button>
                </div>
            </div>
        </form>
    </div>
    <x-file id="files-container" class="absolute w-[96%] border-solid border-y-[1px] border-gray-200 overflow-y-scroll top-[115px] bottom-14 right-[1px]">
        @foreach($files as $file)
        <x-file.item :file="$file" class="hover:bg-gray-200 relative w-full" />
        @endforeach
    </x-file> -->
</x-file>