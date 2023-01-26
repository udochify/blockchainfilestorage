@props(['file'])
@php
    $creation_date = new DateTime($file->created_at);
    $creation_time = $creation_date->format('D, M j, Y \a\t g:i:s A');

    $styles = array('rar'=>['0 0','archive'],'pptx'=>['-55px 0','document'],'xlsx'=>['-111px 0','document'],'docx'=>['-167px 0','document'],
                    'dmg'=>['0 -66px','archive'],'apk'=>['-55px -66px','archive'],'zip'=>['-111px -66px','archive'],'png'=>['-167px -66px','picture'],
                    'psd'=>['0 -133px','archive'],'log'=>['-55px -133px','document'],'txt'=>['-55px -133px','document'],'csv'=>['-55px -133px','document'],'js'=>['-55px -133px','document'],'htm'=>['-55px -133px','document'],
                    'mp3'=>['-111px -133px','audio'],'wav'=>['-111px -133px','audio'],'ogg'=>['-111px -133px','audio'],'mp4'=>['-167px -133px','video'],
                    'ppt'=>['0 -200px','document'],'jpg'=>['-55px -200px','picture'],'xls'=>['-111px -200px','document'],'ai'=>['-167px -200px','archive'],
                    'css'=>['0 -267px','document'],'html'=>['-55px -267px','document'],'pdf'=>['-111px -267px','document'],'doc'=>['-167px -267px','document'],
                    'iso'=>['0 -66px','archive'],'3gp'=>['-167px -133px','video'],'avi'=>['-167px -133px','video'],'webm'=>['-167px -133px','video'],'jpeg'=>['-55px -200px','picture'],'gif'=>['-55px -200px','picture']);
@endphp
<div id="ajax-file{{$file->id}}" {{ $attributes->merge(['class'=>'flex flex-row items-end p-1 border-b border-solid border-b-gray-200']) }}>
    <a class="iframe-link {{ $styles[substr(strrchr($file->name,'.'),1)][1] }} leading-[0] m-0" href="{{ asset('storage/'.$file->file_path) }}">
        <div class="flex-shrink-0 h-11 w-9" style="display: inline-block; background-image: url(img/file-icons.png); background-repeat: no-repeat; background-attachment: scroll; background-position: {{ $styles[substr(strrchr($file->name,'.'),1)][0] ?? $styles['txt'] }}">
        </div>
    </a>
    <div class="flex flex-col items-start ml-2">
        <div class="flex flex-row">
            <div id="download{{$file->id}}" title="download file" class="inline-block px-2 mr-1 text-xs leading-[1.125rem] cursor-pointer hover:bg-blue-500 font-semibold rounded-full bg-blue-400 text-white ajax-btn">
                download
            </div>
            <div id="delete{{$file->id}}" title="delete file" class="delete{{$file->id}} inline-block px-2 mr-1 text-xs leading-[1.125rem] cursor-pointer hover:bg-red-500 font-semibold rounded-full bg-red-400 text-white ajax-btn">
                delete
            </div>
            <div id="verify{{$file->id}}" title="verify file" class="verify{{$file->id}} inline-block px-2 mr-1 text-xs leading-[1.125rem] cursor-pointer hover:bg-green-500 font-semibold rounded-full bg-green-400 text-white ajax-btn">
                verify
            </div>
            <div class="inline-block pr-2 text-sm font-medium text-gray-900">
                {{ $file->getFileSize() }}
            </div>
        </div>
        <a class="iframe-link {{ $styles[substr(strrchr($file->name,'.'),1)][1] }} leading-[0] m-0" href="{{ asset('storage/'.$file->file_path) }}">
            <div title="{{ $file->get_fullname() }}"  class="text-sm -mb-[2px] mt-[2px] text-gray-500">
                {{ $file->get_name(30) }}
            </div>
        </a>
        <form action="{{ route('files.download', $file->id) }}" method="POST" class="download{{$file->id}} hidden">@csrf</form>
        <form action="{{ route('files.crc.post', $file->id) }}" class="crc-post{{$file->id}} hidden">@csrf</form>
        <form action="{{ route('files.delete', $file->id) }}" class="delete{{$file->id}} hidden">@csrf</form>
    </div>
    <div class="flex flex-col border-l border-l-gray-300 border-solid h-full w-fit pl-2">
        <div class="flex flex-row items-center p-[2px]">
            <div class="w-4 h-auto"><img class="w-full" src="img/icons/success-icon.png" /></div>
            <p class="text-gray-900 w-fit ml-1"><strong>Uploaded</strong> on {{ $creation_time }}</p>
        </div>
        <div id="verify-panel{{$file->id}}" class="verifier flex flex-row items-center p-[2px]">
            <x-file.verification :file="$file" />
        </div>
    </div>
    <div id="loading-gif-upload" class="loading-gif w-11 ml-2 my-[-5px]">
                
    </div>
</div>