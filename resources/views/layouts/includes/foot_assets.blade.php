<!-- jQuery -->
<script src="{{ asset('assets/js/jquery.js') }}"></script>

<!-- Bootstrap Core JavaScript -->
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>

<!-- Common JavaScript -->
<script src="{{ asset('assets/js/common.js') }}"></script>

<script>
window.loadingInProgress    = false;
request                     = "";
$(function(){
    
});
function abortAjaxRequests()
{
    //kill previous requests
    if(request != ""){
        request.abort();
    }
}
function showErrors(response){
    var errors = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
    $.each(response, function(key, value){
        errors += value[0]+"<br>";
    });
    errors += "</div>"
    $(".alerts")[0].innerHTML = errors;
}
function showError(message){
    var errors = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
    errors += message;
    errors += "</div>";
    $(".alerts")[0].innerHTML = errors;
}

function showSuccess(message){
    var html = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
    html += message;
    html += '</div>';
    $(".alerts")[0].innerHTML = html;
}

function updateCartStats(response)
{
    $('#cart_count').html(response.cartCount);
}

function updateFavStats(response)
{
    $('#fav_count').html(response.favCount);
}

/*
 * url: desired url
 * product_id: It is product id
 * data: data array
 */
function productRequest(url, method, data){
    if ((window.loadingInProgress === true)) {
        return false;
    }
    if(method == null){
        method = "GET";
    }
    
    request = $.ajax({
        url: url,
        method: method,
        data: data,
        processData: false,
        contentType: false,
        beforeSend: function () {
            window.loadingInProgress = true;
            $('.loading').show();
            
        },
        error: function(XMLHttpRequest){
            if(XMLHttpRequest.status == 422){ //validation failed
                showErrors(XMLHttpRequest.responseJSON);
            }
            window.loadingInProgress = false;
            $('.loading').hide();
        },
        complete: function() {
            window.loadingInProgress = false;
            $('.loading').hide();
        }
    });
}
</script>