jQuery(document).ready(function($) {
    $(".anps_all_pages").change(function(){
        var page_url;
        page_url = $( "select option:selected" ).attr("data-url");
        if(page_url) {
            window.location = page_url;
        }
    });

    $(function() {
        $("#jumppage_menu_meta .inside").organicTabs({
        	"speed": 0
        });
    });

    $('#jumppage_menu_meta .list-wrap').children('ul.site-navigation').eq(0).removeClass('hide');
    $('#jumppage_menu_meta .tabnav li a').removeClass('current');
     $('#jumppage_menu_meta .tabnav li a').eq(0).addClass('current');

});



