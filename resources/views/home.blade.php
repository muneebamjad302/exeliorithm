@extends('app')

@section('body')

<center><h1>Banks Notifications </h1></center>

<form id='notifications_form'>
    @csrf

    <div class="form-group">
        <label for="transaction_days">Transaction days</label>
        <input type="number" min="1" required class="form-control" placeholder="Enter transaction_days" name="transaction_days" id="transaction_days">
    </div>

    <div class="form-group">
      <label for="transaction_data">Transaction Data</label>
        <div class="transaction_details">

        </div>
    </div>

    <div class="form-group">
        <label for="trailing_days">Trailing day</label>
        <input type="text" class="form-control" required placeholder="Enter Trailing day" name="trailing_days" id="trailing_days">
    </div>
 
    <button  id="buttonSubmit" disabled class="btn btn-primary text-center">Submit</button>
</form>
    
@endsection


@section('scripts')
<script>

$(document).ready( function () {

    $("#trailing_days").blur(function () {

        var trailing_days=$("#trailing_days").val()
        var transaction_days=$("#transaction_days").val()
        if(transaction_days < 1 || transaction_days > 200000 )
        {
            alert('Days should be between 1 and 200000')
            $("#transaction_days").val('')
        }

        if(trailing_days < 1 || trailing_days > transaction_days  ){
            alert('Trailing Days should be greater or equal to 1 and less than transaction numbers')
            $("#trailing_days").val('')
        }


        if(transaction_days > trailing_days)
        {
            $('#buttonSubmit').attr('disabled', false);
            var html = '';
                for (let index = 0; index <transaction_days; index++)
            {
                html += '<div class="form-control">';
                html += '<input type="number" required name="transaction_data[]" id="transaction_data" class="form-control" placeholder="Enter transaction_data" autocomplete="off">';
                html += '</div>';
            }
        
            $('.transaction_details').append(html);

        }
        else{
            alert("Total Transection should be greater than Trailing days")
            $("#transaction_days").val('')
            $(".transaction_details").children().remove()
        }
    });

        $('#notifications_form').submit(function(e) {
        e.preventDefault()
        var values = $("input[name='transaction_data[]']")
              .map(function(){return $(this).val();}).get();
        $.ajax({
            url:'/notifications', method:"POST",
            data:{
            "_token": "{{ csrf_token() }}",
            'transactionAmount': values,
            'numAndDays': $("#trailing_days").val(),
            }
        })
        .done(function(resp) {
            console.log(resp)
            $.confirm({
                icon: 'success',
                type: 'blue',
                title: 'Customer will recieve '+ resp + " notifications",
                buttons: {
                    Ok:function(){
                    window.location.reload()
                    }
                }
            });
        })
        .fail(function(err) {
            var message = "";
            var errors = $.parseJSON(err.responseText).errors ;
            var keys = Object.keys($.parseJSON(err.responseText).errors); var i;
            for (i = 0; i < keys.length; ++i) {
            message +=errors[keys[i]][0]+" \n";
            }
            $.confirm({
                icon: 'error',
                type: 'red',
                title: message,
                buttons: {
                    Ok:function(){
                        return
                    }
                }
            });
        });
    })

});


</script>

@endsection