
function readURL(input){
    if(input.files && input.files[0]){
        let reader=new FileReader();
        reader.onload=function (e) {
            $('#target_image').attr('src',e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

$("#change_image").change(function () {
    readURL(this);
});


$(document).ready(function () {
    //=====remove alert message===
    setTimeout(function () {
        $('.alert').hide('slow');
        },2000);

    $('#privilege_id').select2();

    CKEDITOR.replace('description');
});