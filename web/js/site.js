/**
 * Created by MadMax on 06.06.2017.
 */
$(function(){
    $(".modalButton").click(function(event) {
        $("#modalHeader").html($(this).attr("modalHeader"));
        $("#modalDialog").modal("show")
            .find("#modalContent")
            .load($(this).attr("contentUrl"));
    });
});
