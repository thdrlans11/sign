@push('css')
<link type="text/css" rel="stylesheet" href="/devScript/plupload/2.3.6/jquery.plupload.queue/css/jquery.plupload.queue.css" />
<link type="text/css" rel="stylesheet" href="/devCss/jquery-ui-timepicker-addon.css" />
@endpush
@push('scripts')
<script src="/devScript/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
<script src="/devScript/plupload/2.3.6/plupload.full.min.js"></script>
<script src="/devScript/plupload/2.3.6/i18n/ko.js"></script>
<script src="/devScript/plupload/2.3.6/jquery.plupload.queue/jquery.plupload.queue.min.js" ></script>
<script src="/devScript/jquery-ui-timepicker-addon.js" ></script>
<script>
$(document).ready(function(){

    $(".dateTimeCalendar").datetimepicker({
		dateFormat:'yy-mm-dd',
		monthNamesShort:[ '1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월' ],
		dayNamesMin:[ '일', '월', '화', '수', '목', '금', '토' ],
		changeMonth:true,
		changeYear:true,
		showMonthAfterYear:true,
		timeFormat:'HH:mm:ss',
		controlType:'select',
		oneLine:true,
	});

    const tinymce_image_upload_handler = (blobInfo, progress) => new Promise((resolve, reject) => {
        const xhr = new XMLHttpRequest();
        xhr.withCredentials = false;
        xhr.open('POST', '/common/tinyUpload');

        xhr.upload.onprogress = (e) => {
            progress(e.loaded / e.total * 100);
        };

        xhr.onload = () => {
            if (xhr.status === 403) {
                reject({message: 'HTTP Error: ' + xhr.status, remove: true});
                return;
            }

            if (xhr.status < 200 || xhr.status >= 300) {
                reject('HTTP Error: ' + xhr.status);
                return;
            }

            const json = JSON.parse(xhr.responseText);

            if (!json || typeof json.location != 'string') {
                reject('Invalid JSON: ' + xhr.responseText);
                return;
            }

            resolve(json.location);
        };

        xhr.onerror = () => {
            reject('Image upload failed due to a XHR Transport error. Code: ' + xhr.status);
        };

        const formData = new FormData();
        formData.append('file', blobInfo.blob(), blobInfo.filename());
        formData.append('_token', $('meta[name=csrf-token]').attr('content'));

        xhr.send(formData);
    });

    tinymce.init({
        selector: 'textarea', // Replace this CSS selector to match the placeholder element for TinyMCE
        language: 'ko_KR',
        plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount'
        ],
        toolbar: 'undo redo | blocks | ' +
                'bold italic backcolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat | help',
        relative_urls : false,
	    remove_script_host : false,
	    convert_urls : true,        
        image_class_list: [
            {title: 'img-responsive', value: 'img-responsive'},
        ],
        image_title: true,
        automatic_uploads: true,
        file_picker_types: 'image',
        images_upload_handler: tinymce_image_upload_handler,
        file_picker_callback: function(cb, value, meta) {
            var input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.onchange = function() {
                var file = this.files[0];

                var reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = function () {
                    var id = 'blobid' + (new Date()).getTime();
                    var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
                    var base64 = reader.result.split(',')[1];
                    var blobInfo = blobCache.create(id, file, base64);
                    blobCache.add(blobInfo);
                    cb(blobInfo.blobUri(), { title: file.name });
                };
            };
            input.click();
        }
   });
   
   $('#plupload').pluploadQueue({
        runtimes : 'html5,flash',
        flash_swf_url : '/script/Moxie.swf',
        silverlight_xap_url : '/script/Moxie.xap',
        url : '{{ route('plUpload', ['directory'=>'board/'.$code]) }}',
        dragdrop: true,
        headers: {
            Accept: 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        filters : {
            max_file_size : '20mb'
        },
        init: {
            PostInit: function(up) {
                $(up.getOption('container')).find('.plupload_button.plupload_start').hide();
            },
            Error: function (up, err) {
                if (err.code === plupload.HTTP_ERROR) {
                    up.stop();
                    alert('파일 업로드 에러 - ' + err.message);
                }
            },
            FileUploaded: function (up, file, info) {
                var data = JSON.parse(info.response);

                if (data.realfile !== undefined) {
                    var file_index = $('#' + file.id).index();
                    $('#plupload').append('<input type="hidden" name="plupload_' + file_index + '_stored_path" value="' + data.realfile + '" />');
                }
            }
        }
    });

    $("input:radio[name='popup']").click(function(){

        var value = $(this).val();

        if( value == "Y" ){
            $(".popupBox").show();
        }else{
            $(".popupBox").find("input:radio").attr("checked",false);
            $(".popupBox").find("input:text").val("");
            $(".popupBox, .popupDetailBox").hide();
        }

    });

    $("input:radio[name='popup_detail']").click(function(){

        var value = $(this).val();

        if( value == "Y" ){
            $(".popupDetailBox").show();
        }else{            
            $(".popupDetailBox").find("input:text").val("");
            $(".popupDetailBox").hide();
        }
        
    });

    $("input:radio[name='popup_select']").click(function(){

        var value = $(this).val();

        if( value == "2" ){
            $(".popupContentBox").show();
        }else{
            tinymce.get("popup_content").setContent("");
            $(".popupContentBox").hide();
        }

    });

    $("input:radio[name='date_type']").click(function(){

        $(".date_box").show();

        if( $(this).val() == "L" ){
            $("#edate").attr("disabled",false);
        }else{
            $("#edate").attr("disabled",true).val("");
        }

    });

});

function preview(){

    var subject = $("#subject").val();
    var width = $("#width").val();
    var height = $("#height").val();
    var top = $("#position_y").val();
    var left = $("#position_x").val();

    if( subject == "" ){
        alert("제목을 입력해주세요.");
        return false;
    }

    if( $("input:radio[name='popup_select']:checked").val() == "1" ){
        var tiny_text = tinymce.get("content").getContent("");
    }else{
        var tiny_text = tinymce.get("popup_content").getContent("");
    }

    tiny_text = tiny_text.replace(/(<([^>]+)>)/ig,"");
    tiny_text = tiny_text.replace(/<br\/>/ig, "\n");
    tiny_text = tiny_text.replace(/<(\/)?([a-zA-Z]*)(\s[a-zA-Z]*=[^>]*)?(\s)*(\/)?>/ig, "");
    
    if(!$.trim(tiny_text)){
        alert('내용을 입력해 주세요.');
        return false;
    }

    var f = document.getElementById("bbs_form");
    var newWin = window.open("", "popUpPreview", "width=" + width + ",height=" + height + ",top=" + top+ ",left=" + left  + ",scrollbars=yes,resizable=yes");
    f.target = "popUpPreview";
    f.action = "{{ route('board.popupPreview', $code) }}";
    f.submit();

    f.action = "{{ route('board.upsert', ['code'=>$code, 'sid'=>!empty($data)?base64_encode($data['sid']):'']) }}"; //다시 액션값 원상복구
}

function check_bbs(f){

    @if( config('site.board')[$code]['UseCategory'] && !isset($parent_data) )
    if( !$("input:radio[name='category']").is(":checked") ){
        alert("행사구분을 선택해주세요.");
        return false;
    }
    @endif
    if( $(f.subject).val() == "" ){
        alert("교육명을 입력해주세요.");
        return false;
    }
    if( !$("input:radio[name='date_type']").is(":checked") ){
        alert("교육기간을 선택해주세요");
        return false;
    }
    if( $(f.sdate).val() == "" ){
        alert("교육시작일을 선택해주세요.");
        return false;
    }
    if( $("input:radio[name='date_type']:checked").val() == "L" ){
        if( $(f.edate).val() == "" ){
            alert("행사종료일을 선택해주세요.");
            return false;
        }
    }
    if( $(f.place).val() == "" ){
        alert("교육장소를 입력해주세요.");
        return false;
    }

    @if( config('site.board')[$code]['UsePopup'] && !isset($parent_data) )
    if( !$("input:radio[name='popup']").is(":checked") ){
        alert("팝업설정 유무를 선택해주세요.");
        return false;
    }
    if( $("input:radio[name='popup']:checked").val() == "Y" ){
        if( !$("input:radio[name='popup_detail']").is(":checked") ){
            alert("팝업 자세히보기 설정 유무를 선택해주세요.");
            return false;
        }
        if( $(f.popup_sdate).val() == "" || $(f.popup_edate).val() == "" ){
            alert("팝업 시작일, 종료일을 설정해주세요.");
            return false;
        }
    }
    @endif

    var tiny_text = tinymce.get("content").getContent("");
    tiny_text = tiny_text.replace(/(<([^>]+)>)/ig,"");
    tiny_text = tiny_text.replace(/<br\/>/ig, "\n");
    tiny_text = tiny_text.replace(/<(\/)?([a-zA-Z]*)(\s[a-zA-Z]*=[^>]*)?(\s)*(\/)?>/ig, "");
    
    if(!$.trim(tiny_text)){
        alert('내용을 입력해 주세요.');
        return false;
    }

    //파일업로드 사용
    @if( config('site.board')[$code]['UseFile'] )
    var $plupload_queue = $('#plupload').pluploadQueue();
    
    if ($plupload_queue.files.length > 0) {
        $plupload_queue.bind('UploadComplete', function(up, files) {
            if ($plupload_queue.total.failed === 0) {
                $("#bbs_form").attr("onsubmit","");
                $("#bbs_form").submit();
            }
        });

        $plupload_queue.start();         
    } else {
        return true;
    }

    return false;
    @else
    return true;
    @endif

}

</script>
@endpush