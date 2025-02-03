@extends('include.layoutPopup')

@push('css')
<link type="text/css" rel="stylesheet" href="/devCss/handsontable.full.min.css">
@endpush

@push('scripts')
<script type="text/javascript" src="/devScript/handsontable.full.min.js"></script>
<script>
	document.addEventListener("DOMContentLoaded", function() {
		
		var data = [{
			name: '',
			name2: '',
			name3: '',
		}],	
		container,
		hot;	
		var example1 = document.getElementById('example1');
		  
		var hot = new Handsontable(example1, {
			data: data,
			//data: Handsontable.helper.createSpreadsheetData(1, 3),
			//colHeaders: true,
			colHeaders: ['이름', '소속', '이메일'],
			colWidths: [200, 200, 200],	
			licenseKey: 'non-commercial-and-evaluation',
			rowHeaders: "✚",
			contextMenu: true,
			columns: [
			{},
			{},
            {}
		]
		});	
		
	  
		var buttons = {
			string: document.getElementById('export-string'),
			stringRange: document.getElementById('export-string-range'),
			blob: document.getElementById('export-blob'),
			file: document.getElementById('export-file')
		};
	  
		var exportPlugin = hot.getPlugin('exportFile');
		var resultTextarea = document.getElementById('result');
	  
		buttons.string.addEventListener('click', function() {
			resultTextarea.value = exportPlugin.exportAsString('csv');
			console.log(resultTextarea.value);
		});
	
		buttons.stringRange.addEventListener('click', function() {
			resultTextarea.value = exportPlugin.exportAsString('csv', {
				exportHiddenRows: true,     // default false, exports the hidden rows
				exportHiddenColumns: true,  // default false, exports the hidden columns
				columnHeaders: false,        // default false, exports the column headers
				rowHeaders: true,           // default false, exports the row headers
				columnDelimiter: '|::|',       // default ',', the data delimiter
				//range: [0, 0, 3, 3]         // data range in format: [startRow, endRow, startColumn, endColumn]
			});
			//console.log(resultTextarea.value);
		});
		buttons.blob.addEventListener('click', function() {
			var blob = exportPlugin.exportAsBlob('csv');
			resultTextarea.value = blob;
			//console.log(blob);
		});
		buttons.file.addEventListener('click', function() {
			exportPlugin.downloadFile('csv', {filename: 'MyFile'});
		});
	});
	
	$(document).ready(function(){
		$('#excel_uploadF').submit(function(){
            if( $("#sendStatus").val() == "N" ){
                swalConfirm('데이터 등록하시겠습니까?', '', function(){
                    $("#sendStatus").val("Y");
                    $('#excel_uploadF').submit();
                });
                return false;
            }
		});
	});
	</script>
@endpush

@section('content')
<div class="win-popup-wrap popup-wrap type2" id="presentation-multi">
    <div class="popup-contents">
        <div class="popup-conbox">
            <div class="popup-contit-wrap">
                <h2 class="popup-contit">명단 일괄 업로드</h2>
            </div>
            <div class="popup-con">
                <form id="excel_uploadF" method="post" action="{{ route('roster.excelUploadUpsert', ['code'=>$code]) }}">
                    {{ csrf_field() }}
                    <input type="hidden" id="sendStatus" value="N"/>                    
                    <fieldset>
                        <legend class="hide">담당자 등록</legend>
                        <div class="bg-box bg-blue mb-30" style="width:90%">
                            * 아래 형식에 맞게 엑셀내용을 복사하여 붙여넣기 해주시기 바랍니다. <br>
                        </div>
                        
                        <div style="width:90%;">
                            <div id="example1" class="hot handsontable htRowHeaders htColumnHeaders" ></div>
                        </div>
                        <table class="cst-table ac" style="display:none;">
                            <tr style="display:none;">
                                <td>
                                    <button id="export-string" class="intext-btn" type="button">Export as a String</button>
                                    <button id="export-blob" class="intext-btn">Export as a blob</button>
                                    <button id="export-file" class="intext-btn">Export as a file</button>
                                </td>
                            </tr>
                            <tr>
                                <td colspan=6 style="padding:0px;">
                                <textarea name="result" id="result" style="height: 100px; min-width: 400px;font-size: 0.813em;border: 1px solid #C5C5C5;"></textarea>
                                </td>
                            </tr>
                        </table>


                        <div class="btn-wrap text-center mb-0">
                            <button type="submit" class="btn btn-type1 color-type4 intext-btn" id="export-string-range">저장</button>
                            <button type="button" class="btn btn-type1 color-type6 colorClose">닫기</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection