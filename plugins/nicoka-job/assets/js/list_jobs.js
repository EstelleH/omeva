jQuery(document).ready(function (n) {
    let container = n(document.body).find(".jobs-listing .ast-container");
    container.after('<div id="nav"></div>');
    let rowsShown = 15;
    let rowsTotal = n('.jobs-listing .job-item').length;
    let numPages = rowsTotal/rowsShown;
    for(i = 0;i < numPages;i++) {
        var pageNum = i + 1;
        n('#nav').append('<a href="#" rel="'+i+'">'+pageNum+'</a> ');
    }
    n('.jobs-listing .job-item').hide();
    n('.jobs-listing .job-item').slice(0, rowsShown).show();
    n('#nav a:first').addClass('active');
    n('#nav a').bind('click', function(){
        n('#nav a').removeClass('active');
        n(this).addClass('active');
        let currPage = n(this).attr('rel');
        let startItem = currPage * rowsShown;
        let endItem = startItem + rowsShown;
        n('.jobs-listing .job-item').css('opacity','0.0').hide().slice(startItem, endItem).
        css('display','table-row').animate({opacity:1}, 300);
    });
});
     
