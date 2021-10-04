
/*
 * myClass
 */
var Events = function($,options){
 
    /*
     * Variables accessible
     * in the class
     */
    var vars = {
        locations: new Array(),
        selectEvent: null,
        selectBox: '#eventid'
    };

 
    /*
     * Can access this.method
     * inside other methods using
     * root.method()
     */
    var root = this;
 
    /*
     * Constructor
     */
    this.construct = function($, options){
        $.extend(vars , options);
    };
    this.remove = function (idlocation) {
        delete vars.locations[idlocation + '_'];
        this.createElements();
    }

    /**
     * Create Options element on selectBox
     */
    this.createElements = function () {
        var dataHtml = '<option value="" class="placeholder">'+ $(vars.selectBox).find('.placeholder').text()+'</option>';
        $(vars.selectBox).empty();
        var key_;
        if (Object.keys(vars.locations).length > 0) {
          //  $(vars.selectBox).removeClass('hide');
            Object.keys(vars.locations).forEach(element => {
    
                if (vars.locations[element]['data'].length > 0) {
                    
                    if (key_ !== element) {
                        if (typeof(key_) !== 'undefined') {
                            dataHtml += '</optgroup>';
                        }
                   
                        dataHtml += '<optgroup label="' + vars.locations[element]['name'] + '">';
                        key_ = element;
                    }
                    $.each(vars.locations[element]['data'], function (key, val_) {
                        var _selected = '';
                        if ($(vars.selectBox).data('selected') && (val_['id'].toString() === $(vars.selectBox).data('selected').toString())) {
                            _selected = 'selected="selected"';
                            $(vars.selectBox).data('selected', '');
                        }
                        var txt = 'Session le ' + val_['date']; 
                        dataHtml += '<option value="'+val_['id']+'" '+_selected+' >' + txt + '</option>';
                    });
                    if (key_ == Object.keys(vars.locations)[Object.keys(vars.locations).length - 1]) {
                        dataHtml += '</optgroup>';
                    }
                }

            });

        } else {
            //$(vars.selectBox).addClass('hide');
        }
        $(vars.selectBox).append(dataHtml);
    };

    this.getFilieres = function (location, callback) {
        //distinct location 
        var nb = 0;
        //each location check
        if (location && location.length > 0) {
            location.forEach(element => {

                var elm = JSON.parse(element);

                if (elm.loc.toString() + '_' in vars.locations) {
                    nb++;
                    if (nb == location.length) return callback(vars.locations);
                } else {
                    $.ajax({
                        type: "POST",
                        url: ajax_object.ajax_url,
                        data: { 'action': 'getEvents', 'loc': elm.loc , 'order':['start','ASC']}
                    }).done(function (data_) {
                        if (data_.length > 0)
                            vars.locations[elm.loc + '_'] = { 'data': data_, 'name': elm.loclabel };
                        nb++;
                        if (nb == location.length) return callback(vars.locations);
                    }).error(function () {
                        nb++;
                        if (nb == location.length) return callback(vars.locations);
                    });
                }
            });
        }
    };

    this.construct($,options);
};
