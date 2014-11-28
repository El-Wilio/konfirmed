$('li > a.categories-click').on('click', function() {
    $(this).closest('ul').children('div').toggleClass('show');
});