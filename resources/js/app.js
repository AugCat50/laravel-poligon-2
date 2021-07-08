// require('./bootstrap');

$('.nav-tabs').on('click', '.main-data-js', function() {
    $('.tab-pane').removeClass('active');
    $('.nav-link').removeClass('active');
    $('#maindata').addClass('active');
    $('.main-data-js').addClass('active');
});

$('.nav-tabs').on('click', '.sec-data-js', function() {
    $('.tab-pane').removeClass('active');
    $('.nav-link').removeClass('active');
    $('#adddata').addClass('active');
    $('.sec-data-js').addClass('active');
});
