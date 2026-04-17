           $('#formid').bind('submit',function(e){
            // $("#formid").on('submit', function(e) {
                e.preventDefault();
                var dataURL = canvas.toDataURL();
                var img =$('#user_image').val();
                //
                //console.log(img);
               if(document.getElementById('canvas').toDataURL() != document.getElementById('blank').toDataURL()){
                var name = $(".name").val();
                var cid = $("#cid").val();
                   console.log(cid);
                var email = $(".email").val();
                var contact = $(".contact").val();
                var address = $(".address").val();
                var dob = $(".dob").val();
                var gender = $(".gender").val();
                $.ajax({
                  type: 'POST',
                  url: "saveCustomerWithCanvas",
                  dataType:'json',    
                  cache: false,
                  data: {
                    dataURL: dataURL,
                    name: name,
                    cid: cid,
                    email: email,
                    contact: contact,
                    address: address,
                    dob: dob,
                    gender: gender
                  },
                  success: function(response){
              if(response.status == 'error') { 
              $(".flashmessage").fadeIn('fast').delay(3000).fadeOut('fast').html(response.message);
                //console.log(response);
              } else if(response.status == 'success') {
                $(".flashmessage").fadeIn('fast').delay(3000).fadeOut('fast').html(response.message);
                //console.log(response);
                window.setTimeout(function() {
                window.location = response.curl;
                }, 3000);
              }
                  }
                });
                } else {
            var formval = $('#formid');
            var data = new FormData(this);
            console.log(data);
            $.ajax({
                type: "POST",
                enctype: "multipart/form-data",
                url: "save_Customer",
                dataType:'json',
                data: data,
                processData: false,
                contentType: false,
                cache: false,
                timeout: 600000,
                
          success: function(response) {
              if(response.status == 'error') { 
              $(".flashmessage").fadeIn('fast').delay(3000).fadeOut('fast').html(response.message);
                  console.log(response);
              } else if(response.status == 'success') {
                  $(".flashmessage").fadeIn('fast').delay(3000).fadeOut('fast').html(response.message);
                  console.log(response);
                window.setTimeout(function() {
                    window.location = response.curl;
                }, 3000);
              }              
          },
          error: function(response) {
            console.error();
          }
            });
                }
            });

            $('#updateformid').bind('submit',function(e){
            // $("#formid").on('submit', function(e) {
                e.preventDefault();
                var dataURL = canvas.toDataURL();
                var img =$('#user_image').val();
                console.log(document.getElementById('canvas').toDataURL());
                console.log(document.getElementById('blank').toDataURL());
                //
                //console.log(img);
               if(document.getElementById('canvas').toDataURL() != document.getElementById('blank').toDataURL()){
                var cusid = $(".cusid").val();
                var name = $(".name").val();
                var email = $(".email").val();
                var contact = $(".contact").val();
                var address = $(".address").val();
                var dob = $(".dob").val();
                var gender = $(".gender").val();
                $.ajax({
                  type: 'POST',
                  url: "updateCustomerWithCanvas",
                  dataType:'json',    
                  cache: false,
                  data: {
                    dataURL: dataURL,
                    cusid: cusid,
                    name: name,
                    email: email,
                    contact: contact,
                    address: address,
                    dob: dob,
                    gender: gender
                  },
                  success: function(response){
              if(response.status == 'error') { 
              $(".flashmessage").fadeIn('fast').delay(3000).fadeOut('fast').html(response.message);
                //console.log(response);
              } else if(response.status == 'success') {
                $(".flashmessage").fadeIn('fast').delay(3000).fadeOut('fast').html(response.message);
                //console.log(response);
                window.setTimeout(function() {
                window.location = response.curl;
                }, 3000);
              }
                  }
                });
                } else {
            var formval = $('#updateformid');
            var data = new FormData(this);
            //console.log(data);
            $.ajax({
                type: "POST",
                enctype: "multipart/form-data",
                url: "updateCustomer",
                dataType:'json',
                data: data,
                processData: false,
                contentType: false,
                cache: false,
                timeout: 600000,
                
          success: function(response) {
          if(response.status == 'error') { 
          $(".flashmessage").fadeIn('fast').delay(3000).fadeOut('fast').html(response.message);
            //console.log(response);
          } else if(response.status == 'success') {
          $(".flashmessage").fadeIn('fast').delay(3000).fadeOut('fast').html(response.message);
          //console.log(response);
          window.setTimeout(function() {
          window.location = response.curl;
          }, 3000);
          }              
          },
          error: function(response) {
            console.error();
          }
            });
                }
            });