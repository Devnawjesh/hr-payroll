    $(document).ready(function() {
        $(".roleupdate").click(function(e) {
            e.preventDefault(e);
            // Get the record's ID via attribute  
            var iid = $(this).attr('data-id');
            console.log(iid);
            $('#myModal').trigger("reset");
            $.ajax({
                url: 'groupDataByID?id=' + iid,
                method: 'GET',
                data: '',
                dataType: 'json',
            }).done(function(response) {
                console.log(response)
                // Populate the form fields with the data returned from server
                $('#myModal').find('[name="groupid"]').val(response.value.user_id).end();
                $('#myModal').find('[name="username"]').val(response.value.full_name).end();
                $('#myModal').find('[name="role"]').val(response.value.user_type).end();
            });
        });
    });
    $("#groupbutton").on("submit", function(event) {
        event.preventDefault();
        //console.log( $( this ).serialize() );
        $.ajax({
            url: "Update_Group",
            type: "POST",
            data: $(this).serialize(),
            success: function(response) {
                location.reload();
            },
            error: function(response) {
                console.error();
            }
        });
    });