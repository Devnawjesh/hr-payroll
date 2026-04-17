 $(document).ready(function() {
        $("#btnSubmit").click(function(event) {

            //stop submit the form, we will post it manually.
            event.preventDefault();

            // Get form
            var formval = $('#UserValueUpdate')[0];

            // Create an FormData object
            var data = new FormData(formval);
            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: "updateValue",
                data: data,
                dataType: 'json',
                processData: false,
                contentType: false,
                cache: false,
                timeout: 600000,
                success: function(response) {
                    if (response.status == 'error') {
                        $(".flashmessage").fadeIn('fast').delay(3000).fadeOut('fast').html(response.message);
                    } else if (response.status == 'success') {
                        $(".flashmessage").fadeIn('fast').delay(3000).fadeOut('fast').html(response.message);
                        var table = $('#example').DataTable();
                        var editedTrIndex = $('[data-id=' + response.id + ']').closest('tr').index();
                        var tr = $('#example tbody tr:eq(' + editedTrIndex + ')');
                        tr.find('td:eq(0)').find('img').attr('src', response.data['image']);
                        tr.find('td:eq(1)').text(response.id);
                        tr.find('td:eq(2)').text(response.data['full_name']);
                        tr.find('td:eq(3)').text(response.data['email']);
                        tr.find('td:eq(4)').text(response.data['gender']);
                        tr.find('td:eq(5)').text(response.data['country']);
                        table.rows(tr).invalidate().draw();
                    }
                },
                error: function(response) {

                }
            });

        });

    });