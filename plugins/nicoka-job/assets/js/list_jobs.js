jQuery(document).ready(function (n) {
    // Create Pager
    let container = n(document.body).find(".jobs-listing .ast-container");
    container.after('<div id="nav"></div>');
    let rowsShown = 15;
    let rowsTotal = n('.jobs-listing .job-item').length;
    let numPages = rowsTotal / rowsShown;
    for (i = 0; i < numPages; i++) {
        var pageNum = i + 1;
        n('#nav').append('<a href="#" rel="' + i + '">' + pageNum + '</a> ');
    }
    n('.jobs-listing .job-item').hide();
    n('.jobs-listing .job-item').slice(0, rowsShown).show();
    n('#nav a:first').addClass('active');
    n('#nav a').bind('click', function () {
        n('#nav a').removeClass('active');
        n(this).addClass('active');
        let currPage = n(this).attr('rel');
        let startItem = currPage * rowsShown;
        let endItem = startItem + rowsShown;
        n('.jobs-listing .job-item').css('opacity', '0.0').hide().slice(startItem, endItem).css('display', 'table-row').animate({opacity: 1}, 300);
    });

    // Create Filter
    n('#item-filter-type').on('change', function () {
        console.log("type : " + this.value);
        let elems = this.value.value == 'all' ? n('.job-item') : n('.job-item[data-type="' + this.value + '"]');
        n('.job-item').not(elems.show()).hide();
    });

    n('#item-filter-department').on('change', function () {
        console.log("department : " + this.value);
        let elems = this.value.value == 'all' ? n('.job-item') : n('.job-item[data-department="' + this.value + '"]');
        n('.job-item').not(elems.show()).hide();
    });

    n('#item-filter-category').on('change', function () {
        console.log("category : " + this.value);
        let elems = this.value == 'all' ? n('.job-item') : n('.job-item[data-category="' + this.value + '"]');
        n('.job-item').not(elems.show()).hide();
    });

    // Create filters
    /*var getUrlParameter = function getUrlParameter(sParam) {
        var sPageURL = decodeURIComponent(window.location.search.substring(1)),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : sParameterName[1];
            }
        }
    };

    var job = getUrlParameter('postes');
    if (job) {
        n(".job_types input").prop('checked', false);
        n(".job_types input#" + job).prop('checked', true);
    }

    n(".job_types input").prop('checked', false);

    n(".categorie_job_types .title").click(function () {

        if (n(this).parent(".categorie_job_types").hasClass("active")) {
            n(this).parent(".categorie_job_types").removeClass("active");
        } else {
            n(this).parent(".categorie_job_types").addClass("active");
        }

    });

    function updateChange() {

        var self = n(this);
        var checked = [];
        n.each(n(".job_types input:checked"), function () {
            checked = checked.concat(n(this).val().split(','));
        });


        var selectLieu = n('.selectLieu').val();

        n('.job_listings tr').each(function () {

            var dataLieu = n(this).data("lieu");
            var dataFiliere = n(this).data("filiere");

            if (checked.length > 0) {
                if ((selectLieu == dataLieu || selectLieu == "Tous") && n.inArray(dataFiliere.toString(), checked) > -1) {
                    n(this).css("display", "block");
                } else {
                    n(this).css("display", "none");
                }
            } else {
                if ((selectLieu == dataLieu || selectLieu == "Tous")) {
                    n(this).css("display", "block");
                } else {
                    n(this).css("display", "none");
                }
            }
        });
    }

    n(document).on('change', '.selectLieu', function () {
        updateChange();
    });

    n(document).on('change', '.job_types input', function () {
        updateChange();
    });*/
});
     
