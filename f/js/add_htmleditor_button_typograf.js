/**
 * http://spbitec.ru/blog/bitrix_new_htmleditor/
 */

BX.addCustomEvent('OnEditorInitedBefore',function(toolbar){
	var _this=this;
	this.AddButton({
		iconClassName:'bxhtmled-button-alstypograf',
		src:'/local/modules/als.typograf/f/images/typograf_button.png',
		id:'als.typograf',
		title:'typograf',

		handler:function(e){
			var content = _this.GetContent();
			var sAjaxPath = "/local/modules/als.typograf/f/ajax/typograf.php";

			$.ajax({
				type:		"POST",
				url:		sAjaxPath,
				dataType: 'json',
				data:		({CONTENT: content}),
				success:	function(data) {
					if(typeof data === 'object') {
						_this.SetContent(data.RESULT, true);
						_this.ReInitIframe();
					} else {
						console.log("Ошибка парсинга данных: ", data);
					}
				},
				error:		function() {
					console.log("Ошибка загрузки ajax скрипта ", data);
				}
			});
		}
	});
});

/**
 * Add button before field of name


$(document).ready(function() {
	$(".adm-detail-content-table.edit-table #NAME")
		.parent()
			.css("position", "relative")
			.append('<div class="als-typograf-input-btn" onclick="als_typograf_input('+"'NAME'"+');">T</div>')
});

function als_typograf_input(id) {
	console.log(id);

	var content = $("#"+id).val();
	var sAjaxPath = "/local/modules/als.typograf/f/ajax/typograf.php";

	if(content.length) {
		$.ajax({
			type:		"POST",
			url:		sAjaxPath,
			data:		({CONTENT: content}),
			success:	function(data) {
				var arResult = JSON.parse(data);

				if(typeof arResult === 'object') {
					$("#"+id).val(arResult.RESULT);

				} else {
					console.log("Ошибка парсинга данных: ", data);

				}
			},
			error:		function() {
				console.log("Ошибка загрузки ajax скрипта ", data);
			}
		});
	}
} */