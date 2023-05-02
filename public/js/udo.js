var token;
var loadingGifOn = $("<img class='w-full h-auto' src='img/preload-elegant.gif?rand=" + Math.random() + "' />");

const init = {
    method: 'GET', cache: 'no-store', credentials: 'include',
    headers: {'X-CSRF-TOKEN': token, 'Accept': 'text/html,application/json'}
};

$(function() {
    token = $("meta[name='csrf-token']").attr('content');

    $('#file-form').on('submit', upload);
    $("div[id^='download']").on('click', download);
    $("div[id^='delete']").on('click', destroy);
    $("div[id^='verify']").on('click', verify);

    decorateLinks();
});

function decorateLinks() {
    
}

function download() {
    $(this).closest("div[id^='ajax-file']").find($("form[class^='download']")).submit();
    return false;
}

function destroy() {
    const target = $(this).closest("div[id^='ajax-file']");
    const formData = new FormData();
    const key = $('#file-key').val();
    if(key == "") {
        alert('Your Private Key is required');
        return false;
    }
    if(!/^(0x)?[0-9a-fA-F]{64}$/.test(key)) {
        alert('Invalid private key!');
        return false;
    }
    formData.append('key', key);
    let init = {
        method: 'POST', credentials: 'include', body: formData,
        headers: {'X-CSRF-TOKEN': token, 'Accept': 'text/html,application/json'}
    };
    target.find('.loading-gif').html(loadingGifOn);
    disableLinks();
    uFetch(target.find($("form[class^='delete']")).prop('action'), init, {
        success: function(data) {
            if(data.success) target.remove();
            $('#ajax-status').html(data.status);
        },
        always: enableLinks
    });

    return false;
}

function verify() {
    const target = $(this).closest("div[id^='ajax-file']");
    target.find('.loading-gif').html(loadingGifOn);
    disableLinks();
    uFetch(target.find($("form[class^='crc-post']")).prop('action'), init, {
        success: function(data) {
            if(data.view) {
                target.find('.verifier').html(data.view);
            }
            $('#ajax-status').html(data.status);
        },
        always: enableLinks
    });
}

function upload() {
    const target = $(this).prop('action');
    const formData = new FormData();
    const myFile = $('#file-input').prop('files')[0];
    const key = $('#file-key').val();
    if(myFile.size > 2*1024*1024) {
        alert('Max file size is 2MB');
        return false;
    }
    if(key == "") {
        alert('Your Private Key is required');
        return false;
    }
    if(!/^(0x)?[0-9a-fA-F]{64}$/.test(key)) {
        alert('Invalid private key!');
        return false;
    }
    formData.append('file', myFile);
    formData.append('key', key);
    const init = {
        method: 'POST', credentials: 'include', body: formData,
        headers: {'X-CSRF-TOKEN': token, 'Accept': 'text/html,application/json'}
    };
    $('#loading-gif-upload').html(loadingGifOn);
    disableLinks();
    uFetch(target, init, {
        success: function(data) {
            if(data.success) {
                $('#file-panel').prepend(data.view);
                $("div[id^='ajax-file']:first-child").find("div[id^='download']").on('click', download);
                $("div[id^='ajax-file']:first-child").find("div[id^='delete']").on('click', destroy);
                $("div[id^='ajax-file']:first-child").find("div[id^='verify']").on('click', verify);
                $("div[id^='ajax-file']:first-child").find("div[id^='share']").on('click', share);
                $("div[id^='ajax-file']:first-child").find("div[id^='unshare']").on('click', unshare);
            }
            $('#ajax-status').html(data.status);
        },
        always: enableLinks
    });

    return false;
}

function uFetch(url, init, callbacks) {
    fetch(url, init).then(response => {
        if(!response.ok) throw new Error('invalid server response: ' + response.statusText);
        if(response.headers.get('content-type')?.includes("text/html")) return response.text();
        if(response.headers.get('content-type')?.includes("application/json")) return response.json();
    }).then(data => {
        if(callbacks.success) callbacks.success(data);
    }).catch(error => {
        console.log(error.message);
        if(callbacks.fail) callbacks.fail();
    }).finally(() => {
        if(callbacks.always) callbacks.always();
    });
}

function disableLinks() {
    $('#ajax-status').html("<p class='text-sm text-green-600'>sending request to blockchain...</p>");
    $('button, .ajax-btn').css('pointer-events', "none");
}

function enableLinks() {
    $('.preload-img').attr('src', '');
    $('.loading-gif').html("");
    $('button, .ajax-btn').css('pointer-events', "");
}