$('#submit').css("visibility", "hidden")
$('#cek').on("click", function(){
    let email = $('#emailDisplay').val()
    let password = $('#password').val()
    if(!email){
        Swal.fire({
            icon: 'error',
            title: 'Email Dibutuhkan!',
        })
    }else{
        $.ajax({
            url: '/getChangePass',
            data: {email: email},
            type: "GET"
        }).done(function(response){
            let data = JSON.parse(response)

            if(data === null){
                Swal.fire({
                    icon: 'error',
                    title: 'Data Tidak Ditemukan!',
                })
            }else{
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil Menemukan Pengguna',
                })
                var html = "";
                html += "<div class='mb-3'>"
                html += "<label for='exampleInputPassword1' class='form-label'>Password</label>"
                html += "<input type='password' class='form-control' id='password' name='password' required>"
                html += "</div>"
                $('#div-password').html(html)
                $('#submit').css("visibility", "")
                $('#cek').remove()
                $('#emailDisplay').attr("disabled", "disabled")
                $('#email').val(data.email)
            }

        })
    }
})
