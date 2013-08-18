// override reload with own ajax call
FileList.reload = function(){
    $.ajax({
        url: OC.filePath('files_trashbin','ajax','list.php'),
        data: {
            dir : $('#dir').val(),
            breadcrumb: true
        },
        success: function(result) {
            FileList.reloadCallback(result);
        }
    });
}

FileList.setCurrentDir = function(targetDir, changeUrl){
    $('#dir').val(targetDir);
    // Note: IE8 handling ignored for now
    if (window.history.pushState && changeUrl !== false){
        url = OC.linkTo('files_trashbin', 'index.php')+"?dir="+ encodeURIComponent(targetDir).replace(/%2F/g, '/'),
        window.history.pushState({dir: targetDir}, '', url);
    }
}
