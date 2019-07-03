/*
* @Author: Thedijje
* @Date:   2017-12-24 01:03:59
* @Last Modified by:   Thedijje
* @Last Modified time: 2017-12-24 01:05:06
*/

function preview_img(input,preview) {
    if (input.files && input.files[0]){
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#'+preview).attr('src', e.target.result);
            $('#'+preview).parent().removeClass("hidden");
            $('#'+preview).parent().addClass("visible");
            //divx.scrollTop      =    divx.scrollHeight;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$('.save_form').click(function(){
	file_select = $('#img_select').val();
	if(file_select==''){
		alert('Please select image to save');
		$('.img_error').removeClass('hidden');

	}else{
		$('.img_error').addClass('hidden');
	}
});