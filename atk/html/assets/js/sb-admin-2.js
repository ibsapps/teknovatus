$(function() {
    $('#side-menu').metisMenu();
});

//Loads the correct sidebar on window load,
//collapses the sidebar on window resize.
// Sets the min-height of #page-wrapper to window size
$(function() {
    $(window).bind("load resize", function() {
        var topOffset = 50;
        var width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $('div.navbar-collapse').addClass('collapse');
            topOffset = 100; // 2-row-menu
        } else {
            $('div.navbar-collapse').removeClass('collapse');
        }

        var height = ((this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height) - 1;
        height = height - topOffset;
        if (height < 1) height = 1;
        if (height > topOffset) {
            $("#page-wrapper").css("min-height", (height) + "px");
        }
    });

    var url = window.location;
    // var element = $('ul.nav a').filter(function() {
    //     return this.href == url;
    // }).addClass('active').parent().parent().addClass('in').parent();
    var element = $('ul.nav a').filter(function() {
        return this.href == url;
    }).addClass('active').parent();

    while (true) {
        if (element.is('li')) {
            element = element.parent().addClass('in').parent();
        } else {
            break;
        }
    }
});

$(document).ready(function(){
    
    //CheckBox Select All
    $('#select_all').click(function() {
        var checkBoxes = $("input[name=cbox\\[\\]]");
        checkBoxes.prop("checked", $('#select_all').prop("checked"));
    });
    
    //CheckBox Batch Button
    $('.batch').click(function(e) {
        e.preventDefault();
        var refUri = window.location.href;
        var arrUri = refUri.split("/");
        arrUri = arrUri.reverse();
        console.log(arrUri);
        $('#batchExec').attr('action', $(this).attr('href')).append('<input type="hidden" name="node" value="'+arrUri[0]+'">');
        var elementsForm = $('#batchExec input[class=cbox]:checked');
        var sourceClick = $(this).attr('href').split("/").sort(function(a, b){return b-a;});
        if(elementsForm.length>0 || sourceClick[0]==="input") {
            $('#batchExec').submit();
        } else {
            $('#notifyAlert').html('').html("<div class=\"alert alert-danger alert-dismissible fade in text-center\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><i class=\"fa fa-warning\"></i> <strong>Perhatian</strong>, Mohon dipilih data yang akan diproses (minimal 1)</div>");
        }
    });
});