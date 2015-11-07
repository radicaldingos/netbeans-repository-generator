$('input#update-plugins').on('change', function(){
    if ($('input#update-plugins').prop('checked')) {
        $('.plugins').show();
    } else {
        $('.plugins').hide();
    }
});

$('input#update-certified').on('change', function(){
    if ($('input#update-certified').prop('checked')) {
        $('.certified').show();
    }
    $('.certified:not(.distribution)').toggle();
    if ($('input#update-certified').prop(':not(checked)')) {
        $('.certified.distribution').toggle();
    }
});

$('input#update-distribution').on('change', function(){
    $('.certified:not(.distribution)').toggle();
    if ($('input#update-certified').prop(':not(checked)')) {
        $('.certified.distribution').toggle();
    }
});