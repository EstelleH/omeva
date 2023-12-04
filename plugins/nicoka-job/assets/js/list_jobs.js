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
        let elems = this.value.value == 'all' ? n('.job-item') : n('.job-item[data-type="' + this.value + '"]');
        n('.job-item').not(elems.show()).hide();
    });

    n('#item-filter-department').on('change', function () {
        let elems = this.value.value == 'all' ? n('.job-item') : n('.job-item[data-department="' + this.value + '"]');
        n('.job-item').not(elems.show()).hide();
    });

    n('#item-filter-category').on('change', function () {
        let elems = this.value == 'all' ? n('.job-item') : n('.job-item[data-category="' + this.value + '"]');
        n('.job-item').not(elems.show()).hide();

        n('#nav').empty();
        let rowsShown = 15;
        let rowsTotal = elems.length;
        let numPages = rowsTotal / rowsShown;
        for (i = 0; i < numPages; i++) {
            var pageNum = i + 1;
            n('#nav').append('<a href="#" rel="' + i + '">' + pageNum + '</a> ');
        }
        elems.hide();
        elems.slice(0, rowsShown).show();
        n('#nav a:first').addClass('active');
        n('#nav a').bind('click', function () {
            n('#nav a').removeClass('active');
            n(this).addClass('active');
            let currPage = n(this).attr('rel');
            let startItem = currPage * rowsShown;
            let endItem = startItem + rowsShown;
            elems.css('opacity', '0.0').hide().slice(startItem, endItem).css('display', 'table-row').animate({opacity: 1}, 300);
        });
    });

    /* *********** */
    /* Dropdown JS */
    /* *********** */
    
    n('.dropdown-element').click(function(e) {
    e.preventDefault();
    e.stopPropagation();
    //n(this).toggleClass('expanded');
    n(this).addClass('expanded');
    if(n('#'+n(e.target).attr('for')).prop('checked') == true)
    {
        n('#'+n(e.target).attr('for')).prop('checked',false);
    }
    else
    {
        n('#'+n(e.target).attr('for')).prop('checked',true);
    }
    });
    
    
    n(document).click(function() {
    n('.dropdown-element').removeClass('expanded');
    });

    n("#select-city").append("<label class='labelOpacity label_category_dropdown'>Catégories de postes</label>"); 

    // Tri par ordre alphabetique de la listes des choix de catégories de postes
    //#########################################################
    var tab = Array();
    Object.entries(categories).forEach(entry => {
        const [key, value] = entry;
        console.log(key, value);
        tab.push({id:key, value:value})
    });

    tab.sort(function (x, y) {
    let a = x.value.toUpperCase(),
        b = y.value.toUpperCase();
    return a == b ? 0 : a > b ? 1 : -1;
    });

    Object.entries(tab).forEach(entry => {
        const [key, value] = entry;
        if(value.id != 16)
        {
            n("#select-city").append("<input type='checkbox' name='Ville' value='" + value.id + "' id='" + value.id + "'/>");
            n("#select-city").append("<label class='labelOpacity' for='" + value.id + "'>"+ value.value +"</label>"); 
        }
      });

      n("#select-city").append("<input type='checkbox' name='Ville' value='16' id='16'/>");
      n("#select-city").append("<label class='labelOpacity' for='16'>Rejoignez l'équipe Omeva</label>"); 

      var tab_selected_categories = Array();
      n('#btn-filter-reset').on('click', function () {
        tab_selected_categories = Array();
        n("#select-city").empty();
        n("#select-city").append("<label class='labelOpacity label_category_dropdown'>Catégories de postes</label>");  
        Object.entries(tab).forEach(entry => {
            const [key, value] = entry;
            if(value.id != 16)
            {
                n("#select-city").append("<input type='checkbox' name='Ville' value='" + value.id + "' id='" + value.id + "'/>");
                n("#select-city").append("<label class='labelOpacity' for='" + value.id + "'>"+ value.value +"</label>"); 
            }
          });
          n("#select-city").append("<input type='checkbox' name='Ville' value='16' id='16'/>");
          n("#select-city").append("<label class='labelOpacity' for='16'>Rejoignez l'équipe Omeva</label>"); 
          
        let elems = n('.job-item');
        n('.job-item').not(elems.show()).hide();
        n('#nav').empty();
        let rowsShown = 15;
        let rowsTotal = elems.length;
        let numPages = rowsTotal / rowsShown;
        for (i = 0; i < numPages; i++) {
            var pageNum = i + 1;
            n('#nav').append('<a href="#" rel="' + i + '">' + pageNum + '</a> ');
        }
        elems.hide();
        elems.slice(0, rowsShown).show();
        n('#nav a:first').addClass('active');
        n('#nav a').bind('click', function () {
            n('#nav a').removeClass('active');
            n(this).addClass('active');
            let currPage = n(this).attr('rel');
            let startItem = currPage * rowsShown;
            let endItem = startItem + rowsShown;
            elems.css('opacity', '0.0').hide().slice(startItem, endItem).css('display', 'table-row').animate({opacity: 1}, 300);
        });
      });

      n('#btn-filter-offer').on('click', function () {
        var checkboxes = document.getElementsByName('Ville');
  
            var tab_selected_categories = [];
  
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].checked) {
                    tab_selected_categories.push(checkboxes[i].value);
                }
            }

        let selectors = "";
        for (let i = 0; i < tab_selected_categories.length; i++) {
            if(i == 0)
            {
                selectors = '.job-item[data-category="' + tab_selected_categories[i] + '"]';
            }
            else
            {
                selectors = selectors + ', .job-item[data-category="' + tab_selected_categories[i] + '"]';
            }
        }

        let elems = n(selectors);
        n('.job-item').not(elems.show()).hide();
        n('#nav').empty();
        let rowsShown = 15;
        let rowsTotal = elems.length;
        let numPages = rowsTotal / rowsShown;
        for (i = 0; i < numPages; i++) {
            var pageNum = i + 1;
            n('#nav').append('<a href="#" rel="' + i + '">' + pageNum + '</a> ');
        }
        elems.hide();
        elems.slice(0, rowsShown).show();
        n('#nav a:first').addClass('active');
        n('#nav a').bind('click', function () {
            n('#nav a').removeClass('active');
            n(this).addClass('active');
            let currPage = n(this).attr('rel');
            let startItem = currPage * rowsShown;
            let endItem = startItem + rowsShown;
            elems.css('opacity', '0.0').hide().slice(startItem, endItem).css('display', 'table-row').animate({opacity: 1}, 300);
        });
    });
 });
     
